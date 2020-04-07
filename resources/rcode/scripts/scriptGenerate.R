library(odbc)
library(tidyverse)
library(plotly)
library(lubridate)
library(chron)
library(clifro)


#args <- commandArgs(trailingOnly = TRUE)
#codigoEstacaoPrimeiraTorre <- args[1]
#dataInicio <- args[2]
#dataFim <- args[3]


codigoEstacaoPrimeiraTorre <- '000579'
dataInicio <- '2019-06-15'
dataFim <- '2019-06-30'


con <- dbConnect(odbc::odbc(),dsn='measures')

primeiraTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codigoEstacaoPrimeiraTorre,"'"))

dias <- seq(from = ymd(dataInicio), to = ymd(dataFim), by='days')

qryMeasures <- paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE SITCODIGO = ",primeiraTorre$CODIGO, 
                        " AND FLDCODIGO = ? AND DTAREG between '",dataInicio," 00:00:00' and '",
                        dataFim," 23:50:00' ORDER BY DTAREG ASC")

horaminuto <- merge(0:23, seq(0, 50, by = 10))
datetime <- merge(dias, chron(time = paste(horaminuto$x, ':', horaminuto$y, ':', 0)))
datetime$dt <- as.POSIXct(paste(datetime$x, datetime$y))
datetime <- datetime[order(datetime$dt), ]
datetime$dt <- as.character(round_date(datetime$dt, "minute"))

row.names(datetime) <- NULL

# Seleciona o maior código VRS (o último measurement adicionado) das torres escolhidas
primeiraTorre["VRSCODIGO"] <- dbGetQuery(con, paste0("SELECT MAX(CODIGO) FROM CFGVRS WHERE SITCODIGO=",primeiraTorre$CODIGO))

fldCodigosPrimeira <- dbGetQuery(con, paste0("SELECT med.FLDCODIGO,fld.FLDNAME FROM MEDICAO med JOIN CFGFLD fld ON med.FLDCODIGO=fld.CODIGO WHERE med.VRSCODIGO=",primeiraTorre$VRSCODIGO))

dataPrimeira <- data.frame(datetime$dt)
names(dataPrimeira) <- c("DTAREG")
#missingPrimeira <- data.frame()

# Para cada sensor da primeira torre, busca pelas leituras
for (i in 1:nrow(fldCodigosPrimeira)) {
  res <- dbSendQuery(con, qryMeasures)
  dbBind(res, c(fldCodigosPrimeira[i,1]))
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

nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)

barometro <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,2],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
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

anemometroSup <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,5],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
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

anemometroInt <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,17],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("",
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
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

anemometroInf <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,11],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
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

windvaneSup <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,9],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
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

windvaneInf <- ggplot() + 
    geom_line(data = dataPrimeira,aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,15],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
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

windvanes <- dataPrimeira %>% dplyr::select(ends_with("Avg")) %>% dplyr::select(starts_with("WV_"))

windrose(speed = dataPrimeira$AN_80_Avg,
         direction =dataPrimeira$WV_76_Avg,
         speed_cuts = seq(0,25,5),
         ggtheme='minimal')

dir <- paste0("/var/www/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,primeiraTorre$ESTACAO)
dir.create(file.path(plotsDir), showWarnings = FALSE)

ggsave(file = "anemometro-sup.png", plot = anemometroSup, device = "png", path = plotsDir)
ggsave(file = "anemometro-int.png", plot = anemometroInt, device = "png", path = plotsDir)
ggsave(file = "anemometro-inf.png", plot = anemometroInf, device = "png", path = plotsDir)
ggsave(file = "barometro.png", plot = barometro, device = "png", path = plotsDir)
ggsave(file = "windvane-sup.png", plot = windvaneSup, device = "png", path = plotsDir)
ggsave(file = "windvane-inf.png", plot = windvaneInf, device = "png", path = plotsDir)

dbDisconnect(con)