suppressPackageStartupMessages(suppressWarnings({
  library(odbc)
  library(tidyverse)
  library(plotly)
  library(lubridate)
  library(chron)
  library(clifro)
}))

args <- commandArgs(trailingOnly = TRUE)
epeArquivo <- args[1]
#epeArquivo <- '000473_20200416_20200430.txt'
epeDir <- 'C:/xampp/htdocs/sistemaMesstechnik/storage/app/public/epe/'

arqOriginal <- file(paste0(epeDir,epeArquivo))
arqModificado <- file(paste0(epeDir,'MOD-',epeArquivo))

lines <- readLines(arqOriginal)
codigoEstacaoTorre <- str_pad(parse_number(lines[1]), 6, pad="0")
dados <- lines[-c(1:27,29,length(lines))]
writeLines(dados,arqModificado)
close(arqOriginal)
close(arqModificado)

dataPrimeira <- read.table(paste0(epeDir,'MOD-',epeArquivo),header=TRUE,sep='|',dec=',')
dataPrimeira$X <- NULL

dataPrimeira <- dataPrimeira %>% mutate(CH01 = as.Date(as.character(CH01), "%Y%m%d"))
dataPrimeira$CH02 <- str_pad(dataPrimeira$CH02, 6, pad="0")

dataPrimeira$DTAREG <- as.POSIXct(paste(dataPrimeira$CH01,dataPrimeira$CH02,sep=' '), format="%Y-%m-%d %H%M%S")

con <- dbConnect(odbc::odbc(),dsn='measurs')

primeiraTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codigoEstacaoTorre,"'"))

nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)

#not_all_na <- function(x) any(!is.na(x))
#sensoresAvg <- dataPrimeira %>% dplyr::select(ends_with("Avg")) %>% select_if(not_all_na) %>% add_column(DTAREG=dataPrimeira$DTAREG, .before=1)

dir <- paste0("C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/")
plotsDir <- paste0(dir,primeiraTorre$ESTACAO)
dir.create(file.path(plotsDir), showWarnings = FALSE)

canais <- c(4,5,6,7,11,13,17,19)

for (i in canais) {
  plot <- ggplot() + 
    geom_line(data = dataPrimeira, aes(x=as.POSIXct(DTAREG), y=dataPrimeira[,i],colour=nomePrimeiraTorre),size=0.3) +
    scale_colour_manual("", 
                        breaks = c(nomePrimeiraTorre),
                        values = c("#56B4E9")) +
    scale_x_datetime(date_breaks = "12 hours" , date_labels = "%d/%b %R") +
    labs(title=lines[i+3],
         x=' ',
         y=' ') +
    theme(axis.text.x=element_text(size=8, angle=45, hjust=1),
          legend.position="bottom",
          panel.grid.major = element_blank(),
          panel.grid.minor = element_blank(),
          panel.background = element_blank(),
          axis.line = element_line(colour = "black"))
  ggsave(file = paste0(names(dataPrimeira[i]),".png"), plot = plot, device = "png", path = plotsDir, height = 4, width = 8)
}


windvanes <- dataPrimeira[c(11,17)]
anemometros <- dataPrimeira[c(7,13,19)]
names(windvanes) <- c(gsub(".*\\((.*)\\).*", "\\1", lines[14]),gsub(".*\\((.*)\\).*", "\\1", lines[20]))
names(anemometros) <- c(gsub(".*\\((.*)\\).*", "\\1", lines[10]),gsub(".*\\((.*)\\).*", "\\1", lines[16]),gsub(".*\\((.*)\\).*", "\\1", lines[22]))

wvAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(windvanes))))
anAlturas <- as.numeric(gsub("_|-|,",".",gsub("m","",names(anemometros))))

for (i in 1:length(wvAlturas)) {
  anemometroPar <- anemometros[first(which(abs(anAlturas-wvAlturas[i])==min(abs(anAlturas-wvAlturas[i]))))]
  
  windrose <- windrose(speed = as.numeric(unlist(anemometroPar)),
                       direction = as.numeric(unlist(windvanes[i])),
                       speed_cuts = seq(0,25,5),
                       legend_title="Velocidades [m/s]",
                       ggtheme='minimal')+labs(title=paste0("Windvane ",names(windvanes[i])))
  ggsave(file = paste0("rosaventos-",i,".png"), plot = windrose, device = "png", path = plotsDir, height = 4, width = 8)
}

for(i in 1:length(unique(dataPrimeira$CH01))) {
  quantidadeRegistros <- sum(dataPrimeira$CH01 == unique(dataPrimeira$CH01)[i])
  if(quantidadeRegistros != 144) {
    cat(paste0("O dia ",unique(dataPrimeira$CH01)[i]," possui ",quantidadeRegistros," registros.\n"))
  }
}
ind <- duplicated(dataPrimeira[,1:2])
for (i in 1:nrow(dataPrimeira[ind,1:2])) {
  cat(paste0("O registro do dia ",dataPrimeira[ind,1:2][i,1]," e hora ",dataPrimeira[ind,1:2][i,2]," estao duplicados.\n"))
}

dbDisconnect(con)