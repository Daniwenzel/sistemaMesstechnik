startt <- Sys.time()
suppressPackageStartupMessages(suppressWarnings({
  library(odbc)
  library(tidyverse)
  library(plotly)
  library(lubridate)
  library(chron)
  library(clifro)
  library(randomcoloR)
}))

# Recebe como parametros da funcao, o codigo da estacao e as datas 
args <- commandArgs(trailingOnly = TRUE)
codigoEstacaoTorre <- args[1]
dataInicio <- args[2]
dataFim <- args[3]

#codigoEstacaoTorre <- '000575'
#dataInicio <- '2020-05-01'
#dataFim <- '2020-05-15'

# Conexao com banco de dados, dsn nomeado measurs configurado dentro do servidor
con <- dbConnect(odbc::odbc(),dsn='measurs')

torre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codigoEstacaoTorre,"'"))

# Cria uma pasta para a torre dentro do MMS
dir <- paste0("C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,torre$ESTACAO)
dir.create(file.path(plotsDir), showWarnings = FALSE)
invisible(do.call(file.remove, list(list.files(plotsDir, full.names = TRUE))))

# Cria uma uma sequencia dos dias selecionados, utilizando intervalos de 10 minutos, para ser usado no dataframe de dados 
dias <- seq(from = ymd(dataInicio), to = ymd(dataFim), by='days')
horaminuto <- merge(0:23, seq(0, 50, by = 10))
datetime <- merge(dias, chron(time = paste(horaminuto$x, ':', horaminuto$y, ':', 0)))
dados <- as.POSIXct(paste(datetime$x,datetime$y))
dados <- dados[order(dados)]
dados <- data.frame(DTAREG = as.character(round_date(dados, "minute")))
row.names(dados) <- NULL

# Seleciona todos os codigos e nomes dos sensores da torre, que estao presentes nas leituras do periodo definido
qrySensores <- paste0("SELECT DISTINCT(reg.FLDCODIGO),fld.FLDNAME,(SELECT epe.DESCANAL FROM EPECANAIS epe ",
                      "JOIN MEDICAO med ON epe.CODIGO=med.EPECODIGO where med.FLDCODIGO=reg.FLDCODIGO) AS EPECANAL,",
                      "(SELECT epe.ABRCANAL FROM EPECANAIS epe JOIN MEDICAO med ON epe.CODIGO=med.EPECODIGO WHERE ",
                      "med.FLDCODIGO=reg.FLDCODIGO) AS ABRCANAL FROM REGDATA reg JOIN CFGFLD fld ON reg.FLDCODIGO=",
                      "fld.CODIGO WHERE reg.DTAREG BETWEEN '",dplyr::first(dados$DTAREG),"' AND '",dplyr::last(dados$DTAREG),"' AND reg.SITCODIGO=",torre$CODIGO)

sensores <- dbGetQuery(con, qrySensores)

# Filtra os sensores encontrados, mantendo sensores das medias e removendo sensores não utilizados
sensores <- dplyr::filter(sensores,grepl('Avg', FLDNAME))
filtro_variaveis_dewi <- "Primary|Control|LBT|Vertical|Baro|Temp|Hum|Analog|Status|Frequency|Power"
sensoresRemovidos <- sensores$FLDNAME[grepl(filtro_variaveis_dewi, sensores$FLDNAME)]
sensores <- sensores %>% dplyr::filter(!grepl(filtro_variaveis_dewi, FLDNAME))


if(nrow(sensores) != 0) {
  # Para cada sensor encontrado, busca por todas as leituras do periodo e monta o dataframe de dados
  for (i in sensores$FLDCODIGO) {
    sensor <- dbGetQuery(con, paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE DTAREG between '",dataInicio," 00:00:00' and '",
                                     dataFim," 23:50:00' AND FLDCODIGO = ",i," ORDER BY DTAREG ASC"))
    # Remove os milisegundos da DTAREG
    sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
    # Nomeia a coluna com o FLDNAME do sensor
    names(sensor) <- c("DTAREG", sensores$FLDNAME[sensores$FLDCODIGO == i])
    
    # quando o nome do sensor (coluna) já existe, altera o modo da tabela para não criar 2 colunas para o mesmo sensor (ocorre quando há + de 1 measurement)
    if (sensores$FLDNAME[sensores$FLDCODIGO == i] %in% names(dados)) {
      dados <- dados %>%
        pivot_longer(-DTAREG) %>%
        rbind(sensor %>% pivot_longer(-DTAREG)) %>%
        na.omit() %>%
        pivot_wider(names_from = name, values_from = value)
    }
    else {
      dados <- merge(dados,sensor,by="DTAREG",all = TRUE)
    }
    rm(sensor)
  }
  
  # Busca pelo nome da torre, retornando a palavra que encontra-se dentro de parenteses, salva no banco de dados
  nomeTorre <- str_extract(string = torre$SITENAME, pattern = "\\([^()]+\\)")
  nomeTorre <- substring(nomeTorre,2,nchar(nomeTorre)-1)
  
  # Filtro para verificar se algum sensor possui todas as leituras = NA
  todos_zeros_na <- function(x) all(x == 0, na.rm=TRUE)
  
  sensoresRemovidos <- c(sensoresRemovidos, dados %>% dplyr::select_if(todos_zeros_na) %>% names())
  dados <- dados[,!(names(dados) %in% sensoresRemovidos)]
  
  torreWarnings <- c()
  
  # Para cada sensor, gera um plot e salva no diretorio da torre
  tryCatch({
    for(iC in 2:ncol(dados)) {
      tituloPlot <- sensores$EPECANAL[sensores$FLDNAME == names(dados[iC])]
      cor <- randomColor(luminosity = "dark")
      nomeArquivo <- paste0(sensores$ABRCANAL[sensores$FLDNAME == names(dados[iC])],".png")
      
      if(is.na(sensores$EPECANAL[sensores$FLDNAME == names(dados[iC])][1])) {
        tituloPlot <- names(dados[iC])
        nomeArquivo <- paste0(names(dados[iC]),".png")
      }
      
      plot <- ggplot() + 
        geom_line(data = dados, aes(x=as.POSIXct(DTAREG), y=unlist(dados[,iC]),colour=nomeTorre),size=0.3) +
        scale_colour_manual("", 
                            breaks = c(nomeTorre),
                            values = c(cor)) +
        scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
        labs(title=tituloPlot,
             x=' ',
             y=' ') +
        theme(axis.text.x=element_text(size=8, angle=45, hjust=1),
              legend.position="bottom",
              panel.grid.major = element_blank(),
              panel.grid.minor = element_blank(),
              panel.background = element_blank(),
              axis.line = element_line(colour = "black"))
      ggsave(file = nomeArquivo, plot = plot, device = "png", path = plotsDir, height = 4, width = 8)
      
      if (any(is.na(dados[,iC]))) {
        torreWarnings <- append(torreWarnings,paste("Foram encontrados",sum(is.na(dados[,iC])),"NA's no sensor",names(dados[iC]),"\n"))
      } 
    }
  }, error = function(err) {
    message(paste0("ERRO ao gerar plots:\n",err))
    message("-\n")
  })
  
  windvanes <- dados %>% dplyr::select(starts_with("WV"))
  anemometros <- dados %>% dplyr::select(grep("AN-|AN_", names(dados)))
  
  # Busca pela altura dos sensores atraves das etiquetas, e converte para valores numericos para serem utilizados na geracao da rosa dos ventos
  wvAlturas <- as.numeric(gsub("_|-",".",gsub("WV_|_Avg","",names(windvanes))))
  anAlturas <- as.numeric(gsub("_|-",".",gsub("AN_|_Avg","",names(anemometros))))
  
  # Para cada windvane, busca pelo anemometro mais proximo (altura mais proxima), e gera o seu plot da rosa dos vento
  tryCatch({
    for (iW in 1:length(wvAlturas)) {
      anemometroPar <- suppressWarnings(anemometros[first(which(abs(anAlturas-wvAlturas[iW])==min(abs(anAlturas-wvAlturas[iW]))))])
      
      if(is.na(sensores$EPECANAL[sensores$FLDNAME == names(windvanes[iW])][1])) {
        tituloPlot <- names(windvanes[iW])
      } else {
        tituloPlot <- sensores$EPECANAL[sensores$FLDNAME == names(windvanes[iW])]
      }
      
      windrose <- windrose(speed = as.numeric(unlist(anemometroPar)),
                           direction = as.numeric(unlist(windvanes[iW])),
                           speed_cuts = seq(0,25,5),
                           legend_title="Velocidades [m/s]",
                           ggtheme='minimal')+labs(title=tituloPlot)
      # E salva como uma imagem png dentro da pasta da torre
      ggsave(file = paste0("rosaventos-",iW,".png"), plot = windrose, device = "png", path = plotsDir, height = 4, width = 8)
    }
  }, error = function(err) {
    message(paste0("ERRO ao gerar rosa dos ventos dos sensores:\n", err))
    message("-\n")
  })
  
  # Se o indice dos lacos de repeticao alcancarem o ultimo valor possivel, o script foi um sucesso.
  if (iC == ncol(dados) && iW == length(wvAlturas)) {
    message("Plots gerados com sucesso!\n")
    message("-\n")
  }
  
  if(length(sensoresRemovidos) > 0) {
    cat(paste0(length(sensoresRemovidos)), " sensores foram removidos pois tinham dados invalidos ou inexistentes:\n")
    sensoresRemovidos <- split(sensoresRemovidos, ceiling(seq_along(sensoresRemovidos)/7))
    for (iSR in 1:length(sensoresRemovidos)) {
      message(paste(sensoresRemovidos[[iSR]], collapse = ' ', sep = ' '))
    }
  }
  
  if(length(torreWarnings) > 0) {
    message(paste("Logs da torre",nomeTorre,":\n"))
    message(torreWarnings)
  }
  
  dbDisconnect(con)
} else {
  message("A torre nao possui registros de sensores no periodo especificado.\n")
}

endd <- Sys.time()
message("-\n")
message(cat("O script finalizou apos ",round(endd-startt,2)," segundos.\n"))