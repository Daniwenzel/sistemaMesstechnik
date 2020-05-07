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

not_all_na <- function(x) any(!is.na(x))
sensoresAvg <- dataPrimeira %>% dplyr::select(ends_with("Avg")) %>% select_if(not_all_na) %>% add_column(DTAREG=dataPrimeira$DTAREG, .before=1)

dir <- paste0("C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,primeiraTorre$ESTACAO)
dir.create(file.path(plotsDir), showWarnings = FALSE)

for(i in 2:ncol(sensoresAvg)) {
  plot <- ggplot() + 
    geom_line(data = sensoresAvg, aes(x=as.POSIXct(DTAREG), y=sensoresAvg[,i],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title=names(sensoresAvg[i]),
         x=' ',
         y=' ') +
    theme(axis.text.x=element_text(size=8, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))
  ggsave(file = paste0(names(sensoresAvg[i]),".png"), plot = plot, device = "png", path = plotsDir, height = 4, width = 8)
}

windvanes <- dataPrimeira %>% dplyr::select(ends_with("Avg")) %>% dplyr::select(starts_with("WV"))
anemometros <- dataPrimeira %>% dplyr::select(ends_with("Avg")) %>% dplyr::select(starts_with("AN"))

wvAlturas <- as.numeric(gsub("_|-",".",gsub("WV_|_Avg","",names(windvanes))))
anAlturas <- as.numeric(gsub("_|-",".",gsub("AN_|_Avg","",names(anemometros))))

for (i in 1:length(wvAlturas)) {
  anemometroPar <- anemometros[first(which(abs(anAlturas-wvAlturas[i])==min(abs(anAlturas-wvAlturas[i]))))]
  
  windrose <- windrose(speed = as.numeric(unlist(anemometroPar)),
                       direction = as.numeric(unlist(windvanes[i])),
                       speed_cuts = seq(0,25,5),
                       legend_title="Velocidades [m/s]",
                       ggtheme='minimal')+labs(title=names(windvanes[i]))
  ggsave(file = paste0("rosaventos-",i,".png"), plot = windrose, device = "png", path = plotsDir, height = 4, width = 8)
}

dbDisconnect(con)