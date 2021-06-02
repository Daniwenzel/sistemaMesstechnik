startt <- Sys.time()
suppressPackageStartupMessages(suppressWarnings({
  library(odbc)
  library(DBI)
  library(tidyverse)
  library(plotly)
  library(lubridate)
  library(chron)
  library(clifro)
  library(randomcoloR)
  library(gridExtra)
}))

args <- commandArgs(trailingOnly = TRUE)
codEstacaoTorreRef <- str_pad(as.character(args[1]), 6, pad="0")
codEstacaoTorreSec <- str_pad(as.character(args[2]), 6, pad="0")
dataInicio <- args[3]
dataFim <- args[4]

#codEstacaoTorreRef <- str_pad(as.character('000473'), 6, pad="0")
#codEstacaoTorreSec <- str_pad(as.character('000474'), 6, pad="0")
#dataInicio <- '2020-05-01'
#dataFim <- '2020-05-15'

con <- dbConnect(odbc::odbc(),dsn='measurs')

# Em computadores que não possuem o dsn registrado, utilizar método de conexão abaixo
# DRIVER: Verificar existencia do driver Firebird, pressionando Windows+R -> odbcad32 -> drivers
# DBNAME: local do banco de dados, incluindo endereço IP do servidor
# USER: nome de usuário
# PASSWORD: senha
# ROLE: cargo, caso exista

# Mais informações em https://www.firebirdsql.org/file/documentation/html/en/refdocs/fbodbc20/firebird-odbc-driver-20-manual.html
#con <- DBI::dbConnect(odbc::odbc(),
#                      DRIVER="Firebird/InterBase(r) driver", 
#                      DBNAME="192.168.1.251:/home/firebird/measurs.gdb",
#                      USER="mstk",
#                      PASSWORD="abc123",
#                      ROLE="consulta")

# Busca pelo registro das torres, usando o codigo da estacao de cada uma
primeiraTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codEstacaoTorreRef,"'"))
segundaTorre <- dbGetQuery(con, paste0("SELECT * FROM SITE sit WHERE sit.ESTACAO='",codEstacaoTorreSec,"'"))

# Fazer tratamento no nome da pasta, posicionando o maior codigo estacao por primeiro, para que nao seja criado 2 pastas para a mesma correlacao (ex: 000148-000142 e 000142-000148)
dir <- "C:/xampp/htdocs/sistemaMesstechnik/public/images/plots/"
maiorCodigo <- max(c(codEstacaoTorreRef, codEstacaoTorreSec))
menorCodigo <- min(c(codEstacaoTorreRef, codEstacaoTorreSec))
plotsDir <- paste0(dir,maiorCodigo,"-",menorCodigo)
dir.create(file.path(plotsDir), showWarnings = FALSE)
invisible(do.call(file.remove, list(list.files(plotsDir, full.names = TRUE))))

# Monta um dataframe com a sequencia de data/hora de acordo com a datas iniciais e finais providas pelo usuario, usa como base para os dataframes de dados
dias <- seq(from = ymd(dataInicio), to = ymd(dataFim), by='days')
horaminuto <- merge(0:23, seq(0, 50, by = 10))
datetime <- merge(dias, chron(time = paste(horaminuto$x, ':', horaminuto$y, ':', 0)))
datetime$dt <- as.POSIXct(paste(datetime$x,datetime$y))
datetime$dt <- datetime$dt[order(datetime$dt)]
row.names(datetime) <- NULL

# Busca pelos codigos de sensores e registros EPE, de cada torre, presentes dentro do intervalo escolhido
qrySensores <- paste0("SELECT DISTINCT(reg.FLDCODIGO),fld.FLDNAME,(SELECT epe.DESCANAL FROM EPECANAIS epe JOIN MEDICAO med ON epe.CODIGO=med.EPECODIGO where med.FLDCODIGO=reg.FLDCODIGO) AS EPECANAL,(SELECT epe.ABRCANAL FROM EPECANAIS epe JOIN MEDICAO med ON epe.CODIGO=med.EPECODIGO WHERE med.FLDCODIGO=reg.FLDCODIGO) AS ABRCANAL FROM REGDATA reg JOIN CFGFLD fld ON reg.FLDCODIGO=fld.CODIGO WHERE reg.DTAREG BETWEEN '",dataInicio," 00:00:00' AND '",dataFim," 23:50:00' AND reg.SITCODIGO=?")

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

filtro_variaveis_dewi <- "Primary|Control|LBT|Vertical|Baro|Temp|Hum|Analog|Status|Frequency|Power"
primeiraRemovidos <- primeiraSensores$FLDNAME[grepl(filtro_variaveis_dewi, primeiraSensores$FLDNAME)]
primeiraSensores <- primeiraSensores %>% dplyr::filter(!grepl(filtro_variaveis_dewi, FLDNAME))
segundaRemovidos <- segundaSensores$FLDNAME[grepl(filtro_variaveis_dewi, segundaSensores$FLDNAME)]
segundaSensores <- segundaSensores %>% dplyr::filter(!grepl(filtro_variaveis_dewi, FLDNAME))

if(nrow(primeiraSensores) != 0 & nrow(segundaSensores) != 0) {
  # Inicializa o dataframe de dados da primeira torre, utilizando o dataframe de referencia gerado acima
  primeiraDados <- data.frame(DTAREG = as.character(round_date(datetime$dt, "minute")))
  
  tryCatch({
    for (i in primeiraSensores$FLDCODIGO) {
      # Para cada sensor, busca pelas suas medicoes, onde o DTAREG estï¿½ entre o intervalo
      sensor <- dbGetQuery(con, paste0("SELECT DTAREG, REGVALUE FROM REGDATA WHERE DTAREG between '",dataInicio," 00:00:00' and '",
                                       dataFim," 23:50:00' AND FLDCODIGO = ",i," ORDER BY DTAREG ASC"))
      # Remove os milisegundos da DTAREG
      sensor$DTAREG <- as.character(round_date(sensor$DTAREG, "minute"))
      # Nomeia a coluna com o FLDNAME do sensor
      names(sensor) <- c("DTAREG", primeiraSensores$FLDNAME[primeiraSensores$FLDCODIGO == i])
      
      # quando o nome do sensor (coluna) jï¿½ existe, altera o modo da tabela para NAO criar 2 colunas para o mesmo sensor (ocorre quando hï¿½ + de 1 measurement)
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
  
  todos_zeros_na <- function(x) all(x == 0, na.rm=TRUE)
  
  primeiraRemovidos <- c(primeiraRemovidos, primeiraDados %>% dplyr::select_if(todos_zeros_na) %>% names())
  primeiraDados <- primeiraDados[,!(names(primeiraDados) %in% primeiraRemovidos)]
  
  segundaRemovidos <- c(segundaRemovidos, segundaDados %>% dplyr::select_if(todos_zeros_na) %>% names())
  segundaDados <- segundaDados[,!(names(segundaDados) %in% segundaRemovidos)]
  
  nomePrimeiraTorre <- str_extract(string = primeiraTorre$SITENAME, pattern = "\\([^()]+\\)")
  nomePrimeiraTorre <- substring(nomePrimeiraTorre,2,nchar(nomePrimeiraTorre)-1)
  nomeSegundaTorre <- str_extract(string = segundaTorre$SITENAME, pattern = "\\([^()]+\\)")
  nomeSegundaTorre <- substring(nomeSegundaTorre,2,nchar(nomeSegundaTorre)-1)
  
  primeiraWarnings <- c()
  segundaWarnings <- c()
  
  # Para cada sensor da torre de referencia, busca pelo seu equivalente dentro da torre secundaria, e gera o plot da correlacao
  tryCatch({
    for (iC in 2:ncol(primeiraDados)) {
      primeiraFld <- names(primeiraDados)[iC]
      # O nome do segundo sensor ï¿½ o FLDNAME da segundaTorre, buscando pelo FLDNAME que possui o canal EPE igual ao primeiro sensor 
      segundaFld <- segundaSensores$FLDNAME[segundaSensores$ABRCANAL == primeiraSensores$ABRCANAL[primeiraSensores$FLDNAME == primeiraFld]][1]
      
      # Se o canal EPE for inexistente (ocorre quando ï¿½ uma bateria, anemometro vertical...), utiliza o prefixo do primeiro sensor na busca
      if(is.na(segundaFld)) {
        segundaFld <- segundaSensores$FLDNAME[startsWith(segundaSensores$FLDNAME, sub("(_|-).*$", "",primeiraFld))][1]
      }
      
      if(segundaFld %in% names(segundaDados)) {
        legendaPrimeiraTorre <- paste0(nomePrimeiraTorre,": ",primeiraFld)
        legendaSegundaTorre <- paste0(nomeSegundaTorre,": ",segundaFld)
        # Atribui a descricao EPE do canal, atï¿½ encontrar um dos caracteres especiais="," "[" "(" e ".", para o titulo do plot
        tituloPlot <- trimws(word(primeiraSensores$EPECANAL[primeiraSensores$FLDNAME == primeiraFld][1],1,sep="\\,|\\[|\\(|\\."))
        
        # Seta os parametros do plot e o salva dentro do diretorio da correlaï¿½ï¿½o
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
      } # segundaFld %in% names(segundaDados)
      else {
        segundaWarnings <- append(segundaWarnings,paste("O sensor",primeiraFld,",da torre",nomePrimeiraTorre,"nï¿½o encontrou o sensor correspondente",segundaFld,",na torre",nomeSegundaTorre,"\n"))
      }
    } #iC in 2:ncol(primeiraDados)
  }, error = function(err) {
    message(paste0("Falha na geraï¿½ï¿½o dos plots:\n",err))
    message("-\n")
  })
  
  primeiraWindvanes <- primeiraDados %>% dplyr::select(starts_with("WV"))
  primeiraAnemometros <- primeiraDados %>% dplyr::select(grep("AN-|AN_", names(primeiraDados)))
  segundaWindvanes <- segundaDados %>% dplyr::select(starts_with("WV"))
  segundaAnemometros <- segundaDados %>% dplyr::select(grep("AN-|AN_", names(segundaDados)))
  
  # Busca pela altura dos sensores atraves das etiquetas, e converte para valores numericos para serem utilizados na geracao da rosa dos ventos
  primeiraWvAlturas <- as.numeric(gsub("_|-",".",gsub("WV_|_Avg","",names(primeiraWindvanes))))
  primeiraAnAlturas <- as.numeric(gsub("_|-",".",gsub("AN_|_Avg","",names(primeiraAnemometros))))
  segundaWvAlturas <- as.numeric(gsub("_|-",".",gsub("WV_|_Avg","",names(segundaWindvanes))))
  segundaAnAlturas <- as.numeric(gsub("_|-",".",gsub("AN_|_Avg","",names(segundaAnemometros))))
  
  tryCatch({
    for (iW in 1:length(primeiraWvAlturas)) {
      primeiraAnemometroPar <- primeiraAnemometros[first(which(abs(primeiraAnAlturas-primeiraWvAlturas[iW])==min(abs(primeiraAnAlturas-primeiraWvAlturas[iW]))))]
      segundaAnemometroPar <- segundaAnemometros[first(which(abs(segundaAnAlturas-segundaWvAlturas[iW])==min(abs(segundaAnAlturas-segundaWvAlturas[iW]))))]
      
      wr1 <- windrose(speed = as.numeric(unlist(primeiraAnemometroPar)),
                      direction = as.numeric(unlist(primeiraWindvanes[iW])),
                      speed_cuts = seq(0,25,5),
                      legend_title="Velocidades [m/s]",
                      ggtheme='minimal')+labs(title=paste0(nomePrimeiraTorre,": Windvane ",names(primeiraWindvanes[iW])))
      wr2 <- windrose(speed = as.numeric(unlist(segundaAnemometroPar)),
                      direction = as.numeric(unlist(segundaWindvanes[iW])),
                      speed_cuts = seq(0,25,5),
                      legend_title="Velocidades [m/s]",
                      ggtheme='minimal')+labs(title=paste0(nomeSegundaTorre,": Windvane ",names(segundaWindvanes[iW])))
      rosaVentos <- grid.arrange(wr1,wr2,ncol=2)
      # E salva a imagem da rosa dos ventos dentro da pasta da torre 
      ggsave(file=paste0("rosaventos-",iW,".png"), plot=rosaVentos, device="png", path=plotsDir, height=4, width=8)
    }
  }, error = function(err) {
    message(paste0("ERRO ao gerar rosa dos ventos dos sensores:\n", err))
    message("-\n")
  })
  
  
  # Se o indice do laco de repeticao alcancar o ultimo valor possivel, o script foi um sucesso.
  if (iC == ncol(primeiraDados) & iW == length(primeiraWvAlturas)) {
    message("Plots gerados com sucesso!\n")
    message("-\n")
  }
  
  if(length(primeiraWarnings) > 0) {
    message(paste("Logs da torre",nomePrimeiraTorre))
    message(cat(primeiraWarnings))
  }
  
  if(length(segundaWarnings) > 0) {
    message(paste("Logs da torre",nomeSegundaTorre))
    message(cat(segundaWarnings))
  }
  
  dbDisconnect(con)
} else {
  message("Nao foi possivel encontrar registros de sensores para o periodo especificado")
}

endd <- Sys.time()
message("-\n")
message(cat("O script demorou ",round(endd-startt,2)," segundos para finalizar.\n "))