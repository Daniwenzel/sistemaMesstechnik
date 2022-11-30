suppressPackageStartupMessages(suppressWarnings({
  #library(odbc)
  library(tidyverse)
  library(plotly)
  library(lubridate)
  library(chron)
  library(clifro)
  library(randomcoloR)
}))

rm(list=ls())

# Recebe o primeiro parametro (nome do arquivo "carregado" em disco) e atribui ao nome do arquivo
args <- commandArgs(trailingOnly = TRUE)
epeArquivo <- args[1]
#epeArquivo <- '000473_20211101_20211115.txt'
#epeArquivo <- '000571_20200201_20200215.txt'


# Diretorio que o webserver salva os arquivos EPE carregados
epeDir <- 'C:/xampp/htdocs/sistemaMesstechnik/storage/app/public/epe/'
#epeDir <- 'C:/Users/Daniel/Desktop/arquivos-dados/epe/'

# Le arquivo EPE, remove o cabecalho e linhas que nao sao utilizadas ("dados","fimdados"), e salva em outro arquivo para que o R consiga ler
arqOriginal <- file(paste0(epeDir,epeArquivo))
arqModificado <- file(paste0(epeDir,'MOD-',epeArquivo))
linhas <- readLines(arqOriginal)
dados <- linhas[grepl('^[[:digit:]]',linhas)]

nroColunas <- str_count(dados[1],'\\|')
cabecalho <- paste0("CH",str_pad(1:nroColunas, 2, pad = "0"),"|",collapse = '')

writeLines(c(cabecalho,dados),arqModificado)

close(arqOriginal)
close(arqModificado)

# Busca pelo codigo da estacao na linha 1
codigoEstacaoTorre <- str_pad(parse_number(linhas[1]), 6, pad="0")

# FAZER FILTRO VERIFICAR SE ENCONTROU UM CODIGOESTACAO VALIDO, CASO NAO,
# SCRIPT IRA ENVIAR ERRO PARA A PAGINA MAS MOSTRARA PLOTS CASO TENHAM SIDO CRIADOS ANTERIORMENTE

# Lista com possiveis caracteres (tamanho fixo = 5), que precisam ser alterados para NA na leitura
stringsNa <- c("    -","   - ","  -  "," -   ","-    "," - ", "-","    -","   -","  -","-")

# Le arquivo modificado e remove coluna "X", criada sem necessidade
dados <- read.table(paste0(epeDir,'MOD-',epeArquivo),header=TRUE,sep='|',dec=',',na.strings = stringsNa)
dados$X <- NULL
#dados2 <- data.frame(lapply(dados, function(x) { gsub("^([ ]*-[ ]*)$", NA, x) }))

# Converte o tipo da coluna CH01 para "data" e adiciona os 0's a esquerda da coluna CH02 que foram removidos durante a leitura 
dados <- dados %>% mutate(CH01 = as.Date(as.character(CH01), "%Y%m%d"))
dados$CH02 <- str_pad(dados$CH02, 6, pad="0")
dados$DTAREG <- as.POSIXct(paste(dados$CH01,dados$CH02,sep=' '), format="%Y-%m-%d %H%M%S")

# Tenta conectar no database atraves do dsn measurs, e busca pelo registro do SITE usando o codigo estacao.
#tryCatch({
#  con <- dbConnect(odbc::odbc(),dsn='measurs')
#  torre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codigoEstacaoTorre,"'"))
  
#  if(nrow(torre) > 0) {
#    nomeTorre <- str_extract(string = torre$SITENAME, pattern = "\\([^()]+\\)")
#    nomeTorre <- substring(nomeTorre,2,nchar(nomeTorre)-1)
#  }
#}, error = function(err) {
#  message('Nao foi possivel conectar no banco de dados.\n')
#})

# Caso nao encontre, atribui codigo estacao no nomeTorre
#if(!exists('nomeTorre')) {
#  nomeTorre <- codigoEstacaoTorre
#}

# Cria o diretorio da torre, caso ainda nao exista
dir <- paste0("C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,codigoEstacaoTorre)
dir.create(file.path(plotsDir), showWarnings = FALSE)
invisible(do.call(file.remove, list(list.files(plotsDir, full.names = TRUE))))

# Os canais EPE relacionados as leituras das medias dos sensores
canais <- c(4,5,6,7,11,13,17,19)

# Para cada canal, gera o plot das medicoes em relacao a data
suppressWarnings({
  for (iC in canais) {
    cor <- randomColor(luminosity = "dark")
    plot <- ggplot() + 
      geom_line(data = dados, aes(x=as.POSIXct(DTAREG), y=dados[,iC],colour=codigoEstacaoTorre),size=0.3) +
      scale_colour_manual("", 
                          breaks = c(codigoEstacaoTorre),
                          values = c(cor)) +
      scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
      labs(title=linhas[iC+3],
           x=' ',
           y=' ') +
      theme(axis.text.x=element_text(size=8, angle=45, hjust=1),
            legend.position="bottom",
            panel.grid.major = element_blank(),
            panel.grid.minor = element_blank(),
            panel.background = element_blank(),
            axis.line = element_line(colour = "black"))
    # E salva uma imagem .png com o numero do canal como nome, dentro do diretorio da torre 
    ggsave(file = paste0(names(dados[iC]),".png"), plot = plot, device = "png", path = plotsDir, height = 4, width = 8)
  }
})

# Cria um dataframe com as colunas 11 e 17 (medias das windvanes), e usa um regex que filtra strings dentro de parenteses (altura) nas linhas 14 e 20
#windvanes <- dados[c(11,17)]
#names(windvanes) <- c(gsub(".*\\((.*)\\).*", "\\1", linhas[14]),gsub(".*\\((.*)\\).*", "\\1", linhas[20]))

# Cria um dataframe com as colunas 7, 13 e 19 (medias dos anemometros), e usa um regex que filtra strings dentro de parenteses (altura) nas linhas 10, 16 e 22
#anemometros <- dados[c(7,13,19)]
#names(anemometros) <- c(gsub(".*\\((.*)\\).*", "\\1", linhas[10]),gsub(".*\\((.*)\\).*", "\\1", linhas[16]),gsub(".*\\((.*)\\).*", "\\1", linhas[22]))

# Extrai os valores numericos das strings de altura, substituindo caracteres (underlines, hifens, virgulas) para atender o formato numerico
#wvAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(windvanes))))
#anAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(anemometros))))

# Para cada windvane, busca pelo anemometro mais proximo (relacao da altura) e gera a sua rosa dos ventos
#for (iW in 1:length(wvAlturas)) {
  #anemometroProx <- anemometros[first(which(abs(anAlturas-wvAlturas[iW])==min(abs(anAlturas-wvAlturas[iW]))))]
  
rosaVentosSuperior <- windrose(speed = as.numeric(unlist(dados[7])),
                               direction = as.numeric(unlist(dados[11])),
                               speed_cuts = seq(0,25,5),
                               legend_title="Velocidades [m/s]",
                               ggtheme='minimal')+labs(title="Windvane Superior")

ggsave(file=paste0("rosaventos-superior.png"), plot=rosaVentosSuperior, device="png", path=plotsDir, height=4, width=8)

rosaVentosInferior <- windrose(speed = as.numeric(unlist(dados[13])),
                               direction = as.numeric(unlist(dados[17])),
                               speed_cuts = seq(0,25,5),
                               legend_title="Velocidades [m/s]",
                               ggtheme='minimal')+labs(title="Windvane Inferior")

ggsave(file=paste0("rosaventos-inferior.png"), plot=rosaVentosInferior, device="png", path=plotsDir, height=4, width=8)
#}

# Se, ao final do script, o indice dos lacos de repeticao 'for(iC...)' dos plots alcancar o ultimo valor possivel, os plots foram criados com sucesso
if(iC == canais[length(canais)]) {
  cat("Plots gerados com sucesso!\n")
} else {
  cat("Houve uma falha na geracao dos plots.\n")
}

# Todas os possiveis periodos dentro de 1 dia de medicao
periodos <- c('000000','001000','002000','003000','004000','005000','010000','011000','012000','013000','014000','015000','020000','021000','022000','023000','024000','025000','030000','031000','032000','033000','034000','035000','040000','041000','042000','043000','044000','045000','050000','051000','052000','053000','054000','055000','060000','061000','062000','063000','064000','065000','070000','071000','072000','073000','074000','075000','080000','081000','082000','083000','084000','085000','090000','091000','092000','093000','094000','095000','100000','101000','102000','103000','104000','105000','110000','111000','112000','113000','114000','115000','120000','121000','122000','123000','124000','125000','130000','131000','132000','133000','134000','135000','140000','141000','142000','143000','144000','145000','150000','151000','152000','153000','154000','155000','160000','161000','162000','163000','164000','165000','170000','171000','172000','173000','174000','175000','180000','181000','182000','183000','184000','185000','190000','191000','192000','193000','194000','195000','200000','201000','202000','203000','204000','205000','210000','211000','212000','213000','214000','215000','220000','221000','222000','223000','224000','225000','230000','231000','232000','233000','234000','235000')

# Para cada dia do intervalo escolhido, verifica se a quantidade de registros eh != 144, se for menor, busca pela data ausente, se for maior, busca por datas duplicadas
for(i in 1:length(unique(dados$CH01))) {
  quantidadeRegistros <- sum(dados$CH01 == unique(dados$CH01)[i])
  if(quantidadeRegistros < 144) {
    ausentes <- !periodos %in% dados$CH02[dados$CH01 == unique(dados$CH01)[i]]
    cat(paste0("Erro! O dia ",unique(dados$CH01)[i]," possui ",quantidadeRegistros," registros.\n"))
    cat(paste0("- O horario ",periodos[ausentes]," esta ausente.\n"))
  }
  else if(quantidadeRegistros > 144) {
    duplicados <- data.frame(table(dados$CH02[dados$CH01 == unique(dados$CH01)[i]]))
    cat(paste0("Erro! O dia ",unique(dados$CH01)[i]," possui ",quantidadeRegistros," registros.\n"))
    cat(paste0("- O horario ",duplicados$Var1[duplicados$Freq > 1], " esta duplicado.\n")) 
  }
}

for (iD in 1:nrow(dados)) {
  if((dados$CH04[iD] < 800 | dados$CH04[iD] > 1060) & !is.na(dados$CH04[iD])) {
    cat(paste0("A leitura ", dados$CH04[iD]," esta inconsistente para o barometro (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH05[iD] < -15 | dados$CH05[iD] > 50) & !is.na(dados$CH05[iD])) {
    cat(paste0("A leitura ", dados$CH05[iD]," esta inconsistente para o termometro (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH06[iD] < 0 | dados$CH06[iD] > 110) & !is.na(dados$CH06[iD])) {
    cat(paste0("A leitura ", dados$CH06[iD]," esta inconsistente para o higrometro (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH07[iD] < 0 | dados$CH07[iD] > 50) & !is.na(dados$CH07[iD])) {
    cat(paste0("A leitura ", dados$CH07[iD]," esta inconsistente para a media do anemometro superior (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH08[iD] < 0 | dados$CH08[iD] > 70) & !is.na(dados$CH08[iD])) {
    cat(paste0("A leitura ", dados$CH08[iD]," esta inconsistente para a maxima do anemometro superior (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH13[iD] < 0 | dados$CH13[iD] > 50) & !is.na(dados$CH13[iD])) {
    cat(paste0("A leitura ", dados$CH13[iD]," esta inconsistente para a media do anemometro inferior (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH14[iD] < 0 | dados$CH14[iD] > 70) & !is.na(dados$CH14[iD])) {
    cat(paste0("A leitura ", dados$CH14[iD]," esta inconsistente para a maxima do anemometro inferior (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH19[iD] < 0 | dados$CH19[iD] > 50) & !is.na(dados$CH19[iD])) {
    cat(paste0("A leitura ", dados$CH19[iD]," esta inconsistente para a media do anemometro intermediario (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
  if((dados$CH20[iD] < 0 | dados$CH20[iD] > 70) & !is.na(dados$CH20[iD])) {
    cat(paste0("A leitura ", dados$CH20[iD]," esta inconsistente para a maxima do anemometro intermediario (",dados$CH01[iD]," ",dados$CH02[iD],")\n"))
  }
}

#if(exists('con')) {
#  dbDisconnect(con)
#}