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

#codigoEstacaoPrimeiraTorre <- '000148'
#codigoEstacaoSegundaTorre <- '000142'
#dataInicio <- '2020-05-01'
#dataFim <- '2020-05-06'

con <- dbConnect(odbc::odbc(),dsn='measurs')
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
  
  #rm(sensor)
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

not_all_na <- function(x) any(!is.na(x))
sensoresPrimeiraAvg <- dataPrimeira %>% dplyr::select(ends_with("Avg")) %>% select_if(not_all_na)
sensoresSegundaAvg <- dataSegunda %>% dplyr::select(ends_with("Avg")) %>% select_if(not_all_na)

relacao <- data.frame(sensor=names(sensoresSegundaAvg), altura=as.numeric(gsub("_|-",".",gsub("AN_|WV_|B_|T_|U_|_Avg","",names(sensoresSegundaAvg)))) )

nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)
nomeSegundaTorre <- str_extract(string = segundaTorre$SITENAME, pattern = "\\([^()]+\\)")
nomeSegundaTorre <- substring(nomeSegundaTorre,2,nchar(nomeSegundaTorre)-1)

dir <- paste0("C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,primeiraTorre$ESTACAO,"-",segundaTorre$ESTACAO)
dir.create(file.path(plotsDir), showWarnings = FALSE)

for (i in 1:ncol(sensoresPrimeiraAvg)) {
  # extrai a substring até o caractere '_' ou '-' e atribui o resultado ao tipo de sensor
  tipoSensor <- sub("_.*|-.*", "", names(sensoresPrimeiraAvg)[i])
  alturaSensor <- as.numeric(gsub("_|-",".",gsub(paste0(tipoSensor,"|Avg|_"),"",names(sensoresPrimeiraAvg[i]))))
  sensoresEncontrados <- dplyr::filter(relacao, grepl(tipoSensor, sensor))
  
  sensorPar <- as.character(sensoresEncontrados$sensor[first(which(abs(sensoresEncontrados$altura-alturaSensor)==min(abs(sensoresEncontrados$altura-alturaSensor))))])

  tituloPlot <- sub("AN","Anemometros",sub("WV","Windvanes",sub("B","Barometros",sub("T","Temperaturas",sub("U","Umidades",tipoSensor)))))
  legendaPrimeiraTorre <- paste0(nomePrimeiraTorre,": ",names(sensoresPrimeiraAvg[i]))
  legendaSegundaTorre <- paste0(nomeSegundaTorre,": ",sensorPar)
  
  plot <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,names(sensoresPrimeiraAvg[i])],colour=legendaPrimeiraTorre),size=0.3) +
    geom_line(data = dataSegunda,aes(x=as.POSIXct(DTAREG), y=dataSegunda[,sensorPar],colour=legendaSegundaTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(legendaPrimeiraTorre,legendaSegundaTorre),
                        values = c("#56B4E9", "#E3265C")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title=tituloPlot,
         x='',y='') +
    theme(axis.text.x=element_text(size=5, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))
    
  ggsave(file = paste0(tituloPlot,".png"), plot = plot, device = "png", path = plotsDir, height = 4, width = 8)
}

dbDisconnect(con)