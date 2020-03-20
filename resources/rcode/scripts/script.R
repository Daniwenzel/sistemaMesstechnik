library(odbc)
library(tidyverse)
library(plotly)
library(lubridate)
library(chron)

#args <- commandArgs(trailingOnly = TRUE)
#codigoEstacaoPrimeiraTorre <- args[1]
#codigoEstacaoSegundaTorre <- args[2]

codigoEstacaoPrimeiraTorre <- '000579'
codigoEstacaoSegundaTorre <- '000581'

#quinzena <- args[3]
#mes <- args[4]
#ano <- args[5]

#primeiraTorre <- 56
#segundaTorre <- 58
quinzena <- 2
mes <- 6
ano <- 2019

con <- dbConnect(odbc::odbc(),dsn='measures')

res <- dbSendQuery(con, "SELECT * FROM SITE sit WHERE sit.ESTACAO=?")
dbBind(res, c(codigoEstacaoPrimeiraTorre))
primeiraTorre <- dbFetch(res)
dbBind(res, c(codigoEstacaoSegundaTorre))
segundaTorre <- dbFetch(res)

# Query da primeira quinzena é diferente da segunda, usando o 'between' e '>', pois alguns meses contém mais dias que outros
if(quinzena==1) {
  dataInicio <- paste0(ano,"-",mes,"-01")
  dataFim <- paste0(ano,"-",mes,"-15")
  dias <- seq(from = ymd(dataInicio), to = ymd(dataFim), by='days')

  qryMeasures <- paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE SITCODIGO = ? AND 
                        FLDCODIGO = ? AND DTAREG between '",dataInicio," 00:00:00' and '",
                        dataFim," 23:50:00' ORDER BY DTAREG ASC")
} else if(quinzena==2) {
  dataInicio <- paste0(ano,"-",mes,"-16")
  dataFim <- ceiling_date(as.Date(dataInicio), "month") - 1
  dias <- seq(from = ymd(dataInicio), to = ymd(dataFim), by='days')

  qryMeasures <- paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE SITCODIGO = ? AND 
                        FLDCODIGO = ? AND DTAREG between '",dataInicio," 00:00:00' and '",
                        dataFim," 23:50:00' ORDER BY DTAREG ASC")
}

horaminuto <- merge(0:23, seq(0, 50, by = 10))
datetime <- merge(dias, chron(time = paste(horaminuto$x, ':', horaminuto$y, ':', 0)))
datetime$dt <- as.POSIXct(paste(datetime$x, datetime$y))
datetime <- datetime[order(datetime$dt), ]
datetime$dt <- as.character(round_date(datetime$dt, "minute"))

row.names(datetime) <- NULL

# Query para retornar os canais EPE da torre
res <- dbSendQuery(con, "SELECT DISTINCT epe.abrcanal,reg.FLDCODIGO,epe.descanal FROM EPECANAIS epe
                   LEFT JOIN REGDATA reg ON reg.DTAREG= ?
                   AND (SELECT mdc.rdb$DB_KEY FROM MEDICAO mdc WHERE 
                   epe.CODIGO=mdc.EPECODIGO AND mdc.SITCODIGO=reg.SITCODIGO
                   AND mdc.FLDCODIGO=reg.FLDCODIGO) is not null WHERE epe.SITCODIGO=?
                   ORDER BY epe.ABRCANAL")

dbBind(res, c(toString(datetime$dt[2]), primeiraTorre$CODIGO))
canaisPrimeira <- dbFetch(res)
dbBind(res, c(toString(datetime$dt[2]), segundaTorre$CODIGO))
canaisSegunda <- dbFetch(res)
dbClearResult(res)

dataPrimeira <- data.frame(datetime$dt)
names(dataPrimeira) <- c("DTAREG")
missingPrimeira <- data.frame()

# Para cada canal EPE que tem um FLDCODIGO (ou seja, para cada sensor), busca as leituras do período
for (i in (1:nrow(canaisPrimeira))[!is.na(canaisPrimeira$FLDCODIGO)]) {
  res <- dbSendQuery(con, qryMeasures)
  dbBind(res, c(primeiraTorre$CODIGO, canaisPrimeira[i,2]))
  sensor <- dbFetch(res)
  # Arredondamento dos milisegundos da data
  sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
  names(sensor) <- c("DTAREG", canaisPrimeira[i,1])
  
  dataPrimeira <- merge(dataPrimeira,sensor,by="DTAREG",all=TRUE)  
  
  missingPrimeira <- merge(missingPrimeira,dataPrimeira$DTAREG[is.na(dataPrimeira[,ncol(dataPrimeira)])])
  
  rm(sensor)
  dbClearResult(res)
}

dataSegunda <- data.frame(datetime$dt)
names(dataSegunda) <- c("DTAREG")

for (i in (1:nrow(canaisSegunda))[!is.na(canaisSegunda$FLDCODIGO)]) {
  res <- dbSendQuery(con, qryMeasures)
  dbBind(res, c(segundaTorre$CODIGO,canaisSegunda[i,2]))
  sensor <- dbFetch(res)
  sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
  names(sensor) <- c("DTAREG", canaisSegunda[i,1])

  dataSegunda <- merge(dataSegunda,sensor,by="DTAREG",all=TRUE)  

  rm(sensor)
  dbClearResult(res)
}

nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)
nomeSegundaTorre <- str_extract(string = segundaTorre$SITENAME, pattern = "\\([^()]+\\)")
nomeSegundaTorre <- substring(nomeSegundaTorre,2,nchar(nomeSegundaTorre)-1)

barometros <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=CH04,colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=CH04,colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Barometros',
         x=' ',
         y='Pressão do ar [hPa]') +
    theme(axis.text.x=element_text(angle=45, hjust=1),legend.position="bottom")

anemometrosSup <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=CH07,colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=CH07,colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Anemometros Superiores',
         x=' ',
         y='Velocidade do vento [m/s]') +
    theme(axis.text.x=element_text(angle=45, hjust=1),legend.position="bottom")

anemometrosInt <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=CH19,colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=CH19,colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("",
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Anemometros Intermediarios',
         x=' ',
         y='Velocidade do vento [m/s]') +
    theme(axis.text.x=element_text(angle=45, hjust=1),legend.position="bottom")

anemometrosInf <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=CH13,colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=CH13,colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Anemometros Inferiores',
         x=' ',
         y='Velocidade do vento [m/s]') +
    theme(axis.text.x=element_text(angle=45, hjust=1),legend.position="bottom")

windvanesSup <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=CH11,colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=CH11,colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Windvanes Superiores',
         x=' ',
         y='Direcao do vento [º]') +
    theme(axis.text.x=element_text(angle=45, hjust=1),legend.position="bottom")

windvanesInf <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=CH17,colour=nomePrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=CH17,colour=nomeSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre,nomeSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title='Windvanes Inferiores',
         x=' ',
         y='Direcao do vento [º]') +
    theme(axis.text.x=element_text(angle=45, hjust=1),legend.position="bottom")

dir <- paste0(getwd(),"/images/plots/")
plotsDir <- paste0(dir,primeiraTorre$ESTACAO,"-",segundaTorre$ESTACAO)
dir.create(file.path(plotsDir), showWarnings = FALSE)

ggsave(file = "barometros.png", plot = barometros, device = "png", path = plotsDir)
ggsave(file = "anemometros-sup.png", plot = anemometrosSup, device = "png", path = plotsDir)
ggsave(file = "anemometros-int.png", plot = anemometrosInt, device = "png", path = plotsDir)
ggsave(file = "anemometros-inf.png", plot = anemometrosInf, device = "png", path = plotsDir)
ggsave(file = "windvanes-sup.png", plot = windvanesSup, device = "png", path = plotsDir)
ggsave(file = "windvanes-inf.png", plot = windvanesInf, device = "png", path = plotsDir)

dbDisconnect(con)