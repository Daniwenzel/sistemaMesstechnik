library(odbc)
library(DBI)
library(tidyverse)
library(plotly)
library(lubridate)
library(chron)

args <- commandArgs(trailingOnly = TRUE)
codigoEstacaoPrimeiraTorre <- args[1]
codigoEstacaoSegundaTorre <- args[2]
dataInicio <- args[3]
dataFim <- args[4]

#codigoEstacaoPrimeiraTorre <- '000579'
#codigoEstacaoSegundaTorre <- '000581'
#dataInicio <- '2019-06-01'
#dataFim <- '2019-06-30'

con <- dbConnect(odbc::odbc(),dsn='measures')
#con <- DBI::dbConnect(odbc::odbc(),
#                      driver='Firebird/InterBase(r) driver',
#                      dbname='measures',
#                      user='sysdba',
#                      password='masterkey',
#                      host='localhost',
#                      port=3050)

res <- dbSendQuery(con, "SELECT * FROM SITE sit WHERE sit.ESTACAO=?")
dbBind(res, c(codigoEstacaoPrimeiraTorre))
primeiraTorre <- dbFetch(res)
dbBind(res, c(codigoEstacaoSegundaTorre))
segundaTorre <- dbFetch(res)
dbClearResult(res)

dias <- seq(from = ymd(dataInicio), to = ymd(dataFim), by='days')

qryMeasures <- paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE SITCODIGO = ? AND ", 
                        "FLDCODIGO = ? AND DTAREG between '",dataInicio," 00:00:00' and '",
                        dataFim," 23:50:00' ORDER BY DTAREG ASC")

horaminuto <- merge(0:23, seq(0, 50, by = 10))
datetime <- merge(dias, chron(time = paste(horaminuto$x, ':', horaminuto$y, ':', 0)))
datetime$dt <- as.POSIXct(paste(datetime$x, datetime$y))
datetime <- datetime[order(datetime$dt), ]
datetime$dt <- as.character(round_date(datetime$dt, "minute"))

row.names(datetime) <- NULL

# Seleciona o maior código VRS (o último measurement adicionado) das torres escolhidas
res <- dbSendQuery(con, "SELECT MAX(CODIGO) FROM CFGVRS WHERE SITCODIGO=?")
dbBind(res, primeiraTorre$CODIGO)
primeiraTorre["VRSCODIGO"] <- dbFetch(res)
dbBind(res, segundaTorre$CODIGO)
segundaTorre["VRSCODIGO"] <- dbFetch(res)
dbClearResult(res)

res <- dbSendQuery(con, "SELECT med.FLDCODIGO,fld.FLDNAME FROM MEDICAO med JOIN CFGFLD fld ON med.FLDCODIGO=fld.CODIGO WHERE med.VRSCODIGO=?")
dbBind(res, primeiraTorre$VRSCODIGO)
fldCodigosPrimeira <- dbFetch(res)
dbBind(res, segundaTorre$VRSCODIGO)
fldCodigosSegunda <- dbFetch(res)
dbClearResult(res)


dataPrimeira <- data.frame(datetime$dt)
names(dataPrimeira) <- c("DTAREG")
missingPrimeira <- data.frame()

# Para cada sensor da primeira torre, busca pelas leituras
for (i in 1:nrow(fldCodigosPrimeira)) {
  res <- dbSendQuery(con, qryMeasures)
  dbBind(res, c(primeiraTorre$CODIGO, fldCodigosPrimeira[i,1]))
  sensor <- dbFetch(res)
  # Removendo os milisegundos da data
  sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
  # Nomeando a coluna com o nome do sensor
  names(sensor) <- c("DTAREG", fldCodigosPrimeira[i,2])
  
  dataPrimeira <- merge(dataPrimeira,sensor,by="DTAREG",all=TRUE)  
  
  #missingPrimeira <- merge(missingPrimeira,dataPrimeira$DTAREG[is.na(dataPrimeira[,ncol(dataPrimeira)])])
  
  rm(sensor)
  dbClearResult(res)
}

dataSegunda <- data.frame(datetime$dt)
names(dataSegunda) <- c("DTAREG")

#for (i in (1:nrow(canaisSegunda))[!is.na(canaisSegunda$FLDCODIGO)]) {
for (i in 1:nrow(fldCodigosSegunda)) {
  res <- dbSendQuery(con, qryMeasures)
  dbBind(res, c(segundaTorre$CODIGO,fldCodigosSegunda[i,1]))
  sensor <- dbFetch(res)
  # Removendo os milisegundos da data
  sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
  # Nomeando a coluna com o nome do sensor
  names(sensor) <- c("DTAREG", fldCodigosSegunda[i,2])

  dataSegunda <- merge(dataSegunda,sensor,by="DTAREG",all=TRUE)  

  rm(sensor)
  dbClearResult(res)
}

nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)
nomeSegundaTorre <- str_extract(string = segundaTorre$SITENAME, pattern = "\\([^()]+\\)")
nomeSegundaTorre <- substring(nomeSegundaTorre,2,nchar(nomeSegundaTorre)-1)

barometros <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,2],colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=dataSegunda[,2],colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Barometros',
         x=' ',
         y='Pressão do ar [hPa]') +
    theme(axis.text.x=element_text(size=5, angle=90, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))

anemometrosSup <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,5],colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=dataSegunda[,5],colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Anemometros Superiores',
         x=' ',
         y='Velocidade do vento [m/s]') +
    theme(axis.text.x=element_text(size=5, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))

anemometrosInt <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,17],colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=dataSegunda[,17],colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("",
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Anemometros Intermediarios',
         x=' ',
         y='Velocidade do vento [m/s]') +
    theme(axis.text.x=element_text(size=5, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))

anemometrosInf <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,11],colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=dataSegunda[,11],colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Anemometros Inferiores',
         x=' ',
         y='Velocidade do vento [m/s]') +
    theme(axis.text.x=element_text(size=5, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))

windvanesSup <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,9],colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=dataSegunda[,9],colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Windvanes Superiores',
         x=' ',
         y='Direcao do vento [º]') +
    theme(axis.text.x=element_text(size=5, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))

windvanesInf <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,15],colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=dataSegunda[,15],colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Windvanes Inferiores',
         x=' ',
         y='Direcao do vento [º]') +
    theme(axis.text.x=element_text(size=5, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))

dir <- paste0("/var/www/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,primeiraTorre$ESTACAO,"-",segundaTorre$ESTACAO)
dir.create(file.path(plotsDir), showWarnings = FALSE)

ggsave(file = "anemometros-sup.png", plot = anemometrosSup, device = "png", path = plotsDir)
ggsave(file = "anemometros-int.png", plot = anemometrosInt, device = "png", path = plotsDir)
ggsave(file = "anemometros-inf.png", plot = anemometrosInf, device = "png", path = plotsDir)
ggsave(file = "barometros.png", plot = barometros, device = "png", path = plotsDir)
ggsave(file = "windvanes-sup.png", plot = windvanesSup, device = "png", path = plotsDir)
ggsave(file = "windvanes-inf.png", plot = windvanesInf, device = "png", path = plotsDir)

dbDisconnect(con)