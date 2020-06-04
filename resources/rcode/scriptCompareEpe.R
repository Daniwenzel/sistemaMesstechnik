suppressPackageStartupMessages(suppressWarnings({
  library(odbc)
  library(tidyverse)
  library(plotly)
  library(lubridate)
  library(chron)
  library(clifro)
  library(randomcoloR)
  library(gridExtra)
}))

# Recebe o primeiro parametro (nome do arquivo "carregado" em disco) e atribui ao nome do arquivo
args <- commandArgs(trailingOnly = TRUE)
primeiroArquivoEpe <- args[1]
segundoArquivoEpe <- args[2]

#primeiroArquivoEpe <- '000475_20200516_20200531.txt'
#segundoArquivoEpe <- '000474_20200516_20200531.txt'

# Diretório que o webserver salva os arquivos EPE carregados
epeDir <- 'C:/xampp/htdocs/sistemaMesstechnik/storage/app/public/epe/'

primeiroOriginal <- file(paste0(epeDir,primeiroArquivoEpe))
primeiroModificado <- file(paste0(epeDir,'MOD-',primeiroArquivoEpe))
segundoOriginal <- file(paste0(epeDir,segundoArquivoEpe))
segundoModificado <- file(paste0(epeDir,'MOD-',segundoArquivoEpe))

primeiraLinhas <- readLines(primeiroOriginal)
primeiraDados <- primeiraLinhas[grepl('^[[:digit:]]',primeiraLinhas)]
segundaLinhas <- readLines(segundoOriginal)
segundaDados <- segundaLinhas[grepl('^[[:digit:]]',segundaLinhas)]

# Gera um cabeçalho com o numero total de colunas 
primeiraNroColunas <- str_count(primeiraDados[1],'\\|')
primeiraCabecalho <- paste0("CH",str_pad(1:primeiraNroColunas, 2, pad = "0"),"|",collapse = '')
segundaNroColunas <- str_count(segundaDados[1],'\\|')
segundaCabecalho <- paste0("CH",str_pad(1:segundaNroColunas, 2, pad = "0"),"|",collapse = '')

writeLines(c(primeiraCabecalho,primeiraDados),primeiroModificado)
writeLines(c(segundaCabecalho,segundaDados),segundoModificado)

# Escreve o resultado no arquivo modificado e fecha os handlers
close(primeiroOriginal)
close(primeiroModificado)
close(segundoOriginal)
close(segundoModificado)

# Busca pelo codigo da estacao na linha 1
codigoEstacaoPrimeiraTorre <- str_pad(parse_number(primeiraLinhas[1]), 6, pad="0")
codigoEstacaoSegundaTorre <- str_pad(parse_number(segundaLinhas[1]), 6, pad="0")

# FAZER FILTRO VERIFICAR SE ENCONTROU UM CODIGOESTACAO VALIDO, CASO NAO, SCRIPT IRA ENVIAR ERRO PARA A PAGINA MAS MOSTRARA PLOTS CASO TENHAM SIDO CRIADOS ANTERIORMENTE

# Lista com possiveis caracteres (tamanho fixo = 5), que precisam ser alterados para NA na leitura
stringsNa <- c("    -","   - ","  -  "," -   ","-    ")

# Le arquivo modificado e remove coluna "X", criada sem necessidade
primeiraDados <- read.table(paste0(epeDir,'MOD-',primeiroArquivoEpe),header=TRUE,sep='|',dec=',',na.strings=stringsNa)
primeiraDados <- Filter(function(x) !all(is.na(x)), primeiraDados)
segundaDados <- read.table(paste0(epeDir,'MOD-',segundoArquivoEpe),header=TRUE,sep='|',dec=',',na.strings=stringsNa)
segundaDados <- Filter(function(x) !all(is.na(x)), segundaDados)
#primeiraDados$X <- NULL

# Converte o tipo da coluna CH01 para "data" e adiciona os 0's à esquerda da coluna CH02 que foram removidos durante a leitura 
primeiraDados <- primeiraDados %>% mutate(CH01 = as.Date(as.character(CH01), "%Y%m%d"))
primeiraDados$CH02 <- str_pad(primeiraDados$CH02, 6, pad="0")
primeiraDados$DTAREG <- as.POSIXct(paste(primeiraDados$CH01,primeiraDados$CH02,sep=' '), format="%Y-%m-%d %H%M%S")

segundaDados <- segundaDados %>% mutate(CH01 = as.Date(as.character(CH01), "%Y%m%d"))
segundaDados$CH02 <- str_pad(segundaDados$CH02, 6, pad="0")
segundaDados$DTAREG <- as.POSIXct(paste(segundaDados$CH01,segundaDados$CH02,sep=' '), format="%Y-%m-%d %H%M%S")

# Conexao com banco de primeiraDados, dsn nomeado measurs configurado dentro do servidor
con <- dbConnect(odbc::odbc(),dsn='measurs')

primeiraTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codigoEstacaoPrimeiraTorre,"'"))
nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)
segundaTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codigoEstacaoSegundaTorre,"'"))
nomeSegundaTorre <- str_extract(string = segundaTorre$SITENAME, pattern = "\\([^()]+\\)")
nomeSegundaTorre <- substring(nomeSegundaTorre,2,nchar(nomeSegundaTorre)-1)

# Cria o diretorio da torre, caso ainda nao exista
dir <- paste0("C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,codigoEstacaoPrimeiraTorre,'-',codigoEstacaoSegundaTorre)
dir.create(file.path(plotsDir), showWarnings = FALSE)
invisible(do.call(file.remove, list(list.files(plotsDir, full.names = TRUE))))

# Os canais EPE relacionados as leituras das medias dos sensores
canais <- c(4,5,6,7,11,13,17,19)

# Para cada canal, gera o plot das medicoes em relacao a data
for (iC in canais) {
  plot <- ggplot() + 
    geom_line(data = primeiraDados, aes(x=as.POSIXct(DTAREG), y=primeiraDados[,iC],colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = segundaDados, aes(x=as.POSIXct(DTAREG), y=segundaDados[,iC],colour=nomeSegundaTorre),size=0.3) +
    
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre, nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title=primeiraLinhas[iC+3],
         x=' ',
         y=' ') +
    theme(axis.text.x=element_text(size=8, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))
  # E salva uma imagem .png com o numero do canal como nome, dentro do diretorio da torre 
  ggsave(file = paste0(names(primeiraDados[iC]),".png"), plot = plot, device = "png", path = plotsDir, height = 4, width = 8)
}

# Cria um dataframe com as colunas 11 e 17 (medias das windvanes), e usa um regex que filtra strings dentro de parenteses (altura) nas primeiraLinhas 14 e 20
primeiraWindvanes <- primeiraDados[c(11,17)]
names(primeiraWindvanes) <- c(gsub(".*\\((.*)\\).*", "\\1", primeiraLinhas[14]),gsub(".*\\((.*)\\).*", "\\1", primeiraLinhas[20]))
segundaWindvanes <- segundaDados[c(11,17)]
names(segundaWindvanes) <- c(gsub(".*\\((.*)\\).*", "\\1", segundaLinhas[14]),gsub(".*\\((.*)\\).*", "\\1", segundaLinhas[20]))
# Cria um dataframe com as colunas 7, 13 e 19 (medias dos anemometros), e usa um regex que filtra strings dentro de parenteses (altura) nas primeiraLinhas 10, 16 e 22
primeiraAnemometros <- primeiraDados[c(7,13,19)]
names(primeiraAnemometros) <- c(gsub(".*\\((.*)\\).*", "\\1", primeiraLinhas[10]),gsub(".*\\((.*)\\).*", "\\1", primeiraLinhas[16]),gsub(".*\\((.*)\\).*", "\\1", primeiraLinhas[22]))
segundaAnemometros <- segundaDados[c(7,13,19)]
names(segundaAnemometros) <- c(gsub(".*\\((.*)\\).*", "\\1", segundaLinhas[10]),gsub(".*\\((.*)\\).*", "\\1", segundaLinhas[16]),gsub(".*\\((.*)\\).*", "\\1", segundaLinhas[22]))

# Extrai os valores numericos das strings de altura, substituindo caracteres (underlines, hifens, virgulas) para atender o formato numerico
primeiraWvAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(primeiraWindvanes))))
primeiraAnAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(primeiraAnemometros))))
segundaWvAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(segundaWindvanes))))
segundaAnAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(segundaAnemometros))))

# Para cada windvane, busca pelo anemometro mais proximo (relacao da altura) e gera a sua rosa dos ventos
for (iW in 1:length(primeiraWvAlturas)) {
  primeiraAnemometroPar <- primeiraAnemometros[first(which(abs(primeiraAnAlturas-primeiraWvAlturas[iW])==min(abs(primeiraAnAlturas-primeiraWvAlturas[iW]))))]
  segundaAnemometroPar <- segundaAnemometros[first(which(abs(segundaAnAlturas-segundaWvAlturas[iW])==min(abs(segundaAnAlturas-segundaWvAlturas[iW]))))]
  
  wr1 <- windrose(speed = as.numeric(unlist(primeiraAnemometroPar)),
                       direction = as.numeric(unlist(primeiraWindvanes[iW])),
                       speed_cuts = seq(0,25,5),
                       legend_title="Velocidades [m/s]",
                       ggtheme='minimal')+labs(title=paste0("Windvane ",names(primeiraWindvanes[iW])))
  wr2 <- windrose(speed = as.numeric(unlist(segundaAnemometroPar)),
                         direction = as.numeric(unlist(segundaWindvanes[iW])),
                         speed_cuts = seq(0,25,5),
                         legend_title="Velocidades [m/s]",
                         ggtheme='minimal')+labs(title=paste0("Windvane ",names(segundaWindvanes[iW])))
  rosaVentos <- grid.arrange(wr1,wr2,ncol=2)
  # E salva a imagem da rosa dos ventos dentro da pasta da torre 
  ggsave(file=paste0("rosaventos-",iW,".png"), plot=rosaVentos, device="png", path=plotsDir, height=4, width=8)
}

# Se, ao final do script, o indice dos laços 'for' dos plots alcançar o último valor possivel, os plots foram criados com sucesso
if(iC == 19) {
  cat("Plots gerados com sucesso!\n")
} else {
  cat("Houve uma falha na geração dos plots.\n")
}

# Todas os intervalos temporais possiveis dentro de 1 dia
periodos <- c('000000','001000','002000','003000','004000','005000','010000','011000','012000','013000','014000','015000','020000','021000','022000','023000','024000','025000','030000','031000','032000','033000','034000','035000','040000','041000','042000','043000','044000','045000','050000','051000','052000','053000','054000','055000','060000','061000','062000','063000','064000','065000','070000','071000','072000','073000','074000','075000','080000','081000','082000','083000','084000','085000','090000','091000','092000','093000','094000','095000','100000','101000','102000','103000','104000','105000','110000','111000','112000','113000','114000','115000','120000','121000','122000','123000','124000','125000','130000','131000','132000','133000','134000','135000','140000','141000','142000','143000','144000','145000','150000','151000','152000','153000','154000','155000','160000','161000','162000','163000','164000','165000','170000','171000','172000','173000','174000','175000','180000','181000','182000','183000','184000','185000','190000','191000','192000','193000','194000','195000','200000','201000','202000','203000','204000','205000','210000','211000','212000','213000','214000','215000','220000','221000','222000','223000','224000','225000','230000','231000','232000','233000','234000','235000')

# Para cada dia do intervalo escolhido, verifica se a quantidade de registros é != 144, se for menor, busca pela data ausente, se for maior, busca por datas duplicadas
for(i in 1:length(unique(primeiraDados$CH01))) {
  quantidadeRegistros <- sum(primeiraDados$CH01 == unique(primeiraDados$CH01)[i])
  if(quantidadeRegistros < 144) {
    ausentes <- !periodos %in% primeiraDados$CH02[primeiraDados$CH01 == unique(primeiraDados$CH01)[i]]
    cat(paste0("Erro! O dia ",unique(primeiraDados$CH01)[i]," possui ",quantidadeRegistros," registros.\n"))
    cat(paste0("- O horario ",periodos[ausentes]," esta ausente.\n"))
  }
  else if(quantidadeRegistros > 144) {
    duplicados <- data.frame(table(primeiraDados$CH02[primeiraDados$CH01 == unique(primeiraDados$CH01)[i]]))
    cat(paste0("Erro! O dia ",unique(primeiraDados$CH01)[i]," possui ",quantidadeRegistros," registros.\n"))
    cat(paste0("- O horario ",duplicados$Var1[duplicados$Freq > 1], " esta duplicado.\n")) 
  }
}

for(i in 1:length(unique(segundaDados$CH01))) {
  quantidadeRegistros <- sum(segundaDados$CH01 == unique(segundaDados$CH01)[i])
  if(quantidadeRegistros < 144) {
    ausentes <- !periodos %in% segundaDados$CH02[segundaDados$CH01 == unique(segundaDados$CH01)[i]]
    cat(paste0("Erro! O dia ",unique(segundaDados$CH01)[i]," possui ",quantidadeRegistros," registros.\n"))
    cat(paste0("- O horario ",periodos[ausentes]," esta ausente.\n"))
  }
  else if(quantidadeRegistros > 144) {
    duplicados <- data.frame(table(segundaDados$CH02[segundaDados$CH01 == unique(segundaDados$CH01)[i]]))
    cat(paste0("Erro! O dia ",unique(segundaDados$CH01)[i]," possui ",quantidadeRegistros," registros.\n"))
    cat(paste0("- O horario ",duplicados$Var1[duplicados$Freq > 1], " esta duplicado.\n")) 
  }
}

dbDisconnect(con)