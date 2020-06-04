startt <- Sys.time()
suppressPackageStartupMessages(suppressWarnings({
  library(odbc)
  library(DBI)
  library(tidyverse)
  library(plotly)
  library(lubridate)
  library(chron)
  library(randomcoloR)
}))

args <- commandArgs(trailingOnly = TRUE)
torreRef <- str_pad(as.character(args[1]), 6, pad="0")
torreSec <- str_pad(as.character(args[2]), 6, pad="0")
dataInicio <- args[3]
dataFim <- args[4]

#torreRef <- str_pad(as.character('000475'), 6, pad="0")
#torreSec <- str_pad(as.character('000474'), 6, pad="0")
#dataInicio <- '2020-05-01'
#dataFim <- '2020-05-15'

con <- dbConnect(odbc::odbc(),dsn='measurs')

# Busca pelo registro das torres, usando o codigo da estacao de cada uma
primeiraTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",torreRef,"'"))
segundaTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",torreSec,"'"))


# Monta um dataframe com a sequencia de data/hora de acordo com as datas providas pelo usuario, usa como base para os dataframes de dados
dias <- seq(from = ymd(dataInicio), to = ymd(dataFim), by='days')
horaminuto <- merge(0:23, seq(0, 50, by = 10))
datetime <- merge(dias, chron(time = paste(horaminuto$x, ':', horaminuto$y, ':', 0)))
datetime$dt <- as.POSIXct(paste(datetime$x,datetime$y))
datetime$dt <- datetime$dt[order(datetime$dt)]
row.names(datetime) <- NULL

# Busca pelos codigos de sensores e registros EPE, de cada torre, presentes dentro do intervalo escolhido
qrySensores <- paste0("SELECT DISTINCT(reg.FLDCODIGO),fld.FLDNAME,(SELECT epe.DESCANAL FROM EPECANAIS epe ",
                      "JOIN MEDICAO med ON epe.CODIGO=med.EPECODIGO where med.FLDCODIGO=reg.FLDCODIGO) AS EPECANAL,",
                      "(SELECT epe.ABRCANAL FROM EPECANAIS epe JOIN MEDICAO med ON epe.CODIGO=med.EPECODIGO WHERE ",
                      "med.FLDCODIGO=reg.FLDCODIGO) AS ABRCANAL FROM REGDATA reg JOIN CFGFLD fld ON reg.FLDCODIGO=",
                      "fld.CODIGO WHERE reg.DTAREG BETWEEN '",dataInicio," 00:00:00' AND '",dataFim," 23:50:00' AND reg.SITCODIGO=?")

res <- dbSendQuery(con, qrySensores)
dbBind(res,primeiraTorre$CODIGO)
primeiraSensores <- dbFetch(res)
dbBind(res,segundaTorre$CODIGO)
segundaSensores <- dbFetch(res)
dbClearResult(res)

# Filtra e ordena os sensores, mantendo apenas as medias, buscando pelo sufixo Avg no campo FLDNAME de cada
primeiraSensores <- dplyr::filter(primeiraSensores,grepl('Avg', FLDNAME))
primeiraSensores <- primeiraSensores[with(primeiraSensores, order(ABRCANAL,FLDNAME)),]
row.names(primeiraSensores) <- NULL
segundaSensores <- dplyr::filter(segundaSensores,grepl('Avg', FLDNAME))
segundaSensores <- segundaSensores[with(segundaSensores, order(ABRCANAL,FLDNAME)),]
row.names(segundaSensores) <- NULL

# Inicializa o dataframe de dados da primeira torre, utilizando o dataframe de referencia gerado acima
primeiraDados <- data.frame(DTAREG = as.character(round_date(datetime$dt, "minute")))

tryCatch({
  for (i in primeiraSensores$FLDCODIGO) {
    # Para cada sensor, busca pelas suas medicoes, onde o DTAREG está entre o intervalo
    sensor <- dbGetQuery(con, paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE DTAREG between '",dataInicio," 00:00:00' and '",
                                     dataFim," 23:50:00' AND FLDCODIGO = ",i," ORDER BY DTAREG ASC"))
    # Remove os milisegundos da DTAREG
    sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
    # Nomeia a coluna com o FLDNAME do sensor
    names(sensor) <- c("DTAREG", primeiraSensores$FLDNAME[primeiraSensores$FLDCODIGO == i])
    
    # quando o nome do sensor (coluna) já existe, altera o modo da tabela para NAO criar 2 colunas para o mesmo sensor (ocorre quando há + de 1 measurement)
    if (primeiraSensores$FLDNAME[primeiraSensores$FLDCODIGO == i] %in% names(primeiraDados)) {
      primeiraDados <- primeiraDados %>%
        pivot_longer(-DTAREG) %>%
        rbind(sensor %>% pivot_longer(-DTAREG)) %>%
        na.omit() %>%
        pivot_wider(names_from = name, values_from = value)
    }
    else {
      primeiraDados <- merge(primeiraDados,sensor,by="DTAREG",all = TRUE)
    }
    rm(sensor)
  }
})

# Faz o mesmo processo para a segunda torre
segundaDados <- data.frame(DTAREG = as.character(round_date(datetime$dt, "minute")))
tryCatch({
  for (i in segundaSensores$FLDCODIGO) {
    sensor <- dbGetQuery(con, paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE DTAREG between '",dataInicio," 00:00:00' and '",
                                     dataFim," 23:50:00' AND FLDCODIGO = ",i," ORDER BY DTAREG ASC"))
    sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
    names(sensor) <- c("DTAREG", segundaSensores$FLDNAME[segundaSensores$FLDCODIGO == i])
    
    if (segundaSensores$FLDNAME[segundaSensores$FLDCODIGO == i] %in% names(segundaDados)) {
      segundaDados <- segundaDados %>%
        pivot_longer(-DTAREG) %>%
        rbind(sensor %>% pivot_longer(-DTAREG)) %>%
        na.omit() %>%
        pivot_wider(names_from = name, values_from = value)
    }
    else {
      segundaDados <- merge(segundaDados,sensor,by="DTAREG",all = TRUE)
    }
    rm(sensor)
  }
})

not_all_na <- function(x) any(!is.na(x))

nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)
nomeSegundaTorre <- str_extract(string = segundaTorre$SITENAME, pattern = "\\([^()]+\\)")
nomeSegundaTorre <- substring(nomeSegundaTorre,2,nchar(nomeSegundaTorre)-1)

dir <- "C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/"

# Fazer tratamento no nome da pasta, posicionando o maior codigo estacao por primeiro, para que nao seja criado 2 pastas
# para a mesma correlacao (ex: 000148-000142 e 000142-000148)
maiorCodigo <- max(c(primeiraTorre$ESTACAO, segundaTorre$ESTACAO))
menorCodigo <- min(c(primeiraTorre$ESTACAO, segundaTorre$ESTACAO))
plotsDir <- paste0(dir,maiorCodigo,"-",menorCodigo)
dir.create(file.path(plotsDir), showWarnings = FALSE)
invisible(do.call(file.remove, list(list.files(plotsDir, full.names = TRUE))))

primeiraWarnings <- c()
segundaWarnings <- c()

# Para cada sensor da torre de referencia, busca pelo seu equivalente dentro da torre secundaria, e gera o plot da correlacao
tryCatch({
  for (iC in 2:ncol(primeiraDados)) {
    primeiraFld <- names(primeiraDados)[iC]
    # O nome do segundo sensor é o FLDNAME da segundaTorre, buscando pelo FLDNAME que possui o canal EPE igual ao primeiro sensor 
    segundaFld <- segundaSensores$FLDNAME[segundaSensores$ABRCANAL == primeiraSensores$ABRCANAL[primeiraSensores$FLDNAME == primeiraFld]][1]
    
    # Se o canal EPE for inexistente (ocorre quando é uma bateria, anemometro vertical...), utiliza o prefixo do primeiro sensor na busca
    if(is.na(segundaFld)) {
      segundaFld <- segundaSensores$FLDNAME[startsWith(segundaSensores$FLDNAME, sub("(_|-).*$", "",primeiraFld))][1]
    }
    
    legendaPrimeiraTorre <- paste0(nomePrimeiraTorre,": ",primeiraFld)
    legendaSegundaTorre <- paste0(nomeSegundaTorre,": ",segundaFld)
    # Atribui a descricao EPE do canal, até encontrar um dos caracteres especiais="," "[" "(" e ".", para o titulo do plot
    tituloPlot <- trimws(word(primeiraSensores$EPECANAL[primeiraSensores$FLDNAME == primeiraFld][1],1,sep="\\,|\\[|\\(|\\."))
    
    # Seta os parametros do plot e o salva dentro do diretorio da correlação
    plot <- ggplot() +
      geom_line(data = primeiraDados,aes(x=as.POSIXct(DTAREG), y=unlist(primeiraDados[, primeiraFld]),colour=legendaPrimeiraTorre),size=0.5) +
      geom_line(data = segundaDados,aes(x=as.POSIXct(DTAREG), y=unlist(segundaDados[,segundaFld]),colour=legendaSegundaTorre),size=0.5) +
      scale_colour_manual("",
                          breaks = c(legendaPrimeiraTorre,legendaSegundaTorre),
                          values = c("#56B4E9", "#E3265C")) +
      scale_x_datetime(date_breaks="12 hours" , date_labels="%d/%b %R") +
      labs(title=tituloPlot,x='',y='') +
      theme(axis.text.x=element_text(size=5, angle=45, hjust=1),
            legend.position="bottom",
            panel.grid.major = element_blank(),
            panel.grid.minor = element_blank(),
            panel.background = element_blank(),
            axis.line = element_line(colour = "black"))
    
    ggsave(file=paste0(primeiraFld,".png"), plot=plot, device="png", path=plotsDir, height=4, width=8)
    
    if (any(is.na(primeiraDados[,primeiraFld]))) {
      primeiraWarnings <- append(primeiraWarnings,paste("Foram encontrados",sum(is.na(primeiraDados[primeiraFld])),"NA's no sensor",primeiraFld,"\n"))
    } 
    if (any(is.na(segundaDados[,segundaFld]))) {
      segundaWarnings <- append(segundaWarnings,paste("Foram encontrados",sum(is.na(segundaDados[segundaFld])),"NA's no sensor",segundaFld,"\n"))
    } 
  }
}, error = function(err) {
  message(paste0("ERRO ao gerar plots:\n",err))
  message("-\n")
})

# Se o indice do laco de repeticao alcancar o ultimo valor possivel, o script foi um sucesso.
if (iC == ncol(primeiraDados)) {
  message("Plots gerados com sucesso!\n")
} else {
  message("-\n")
  message("Falha na geracao dos Plots.\n")
}

if(length(primeiraWarnings) > 0) {
  message(paste("Logs da torre",nomePrimeiraTorre,":\n"))
  message(primeiraWarnings)
}

if(length(segundaWarnings) > 0) {
  message(paste("Logs da torre",nomeSegundaTorre,":\n"))
  message(segundaWarnings)
}

dbDisconnect(con)

endd <- Sys.time()
message(cat("O script demorou ",round(endd-startt,2)," segundos para finalizar.\n "))