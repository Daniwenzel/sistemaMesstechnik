<?php

namespace App\Console\Commands;

use App\Models\ArquivoLog;
use App\Models\Tower;
use Illuminate\Console\Command;
use Icewind\SMB\ServerFactory;
use Icewind\SMB\BasicAuth;

class BuscarDadosServidor extends Command
{

    protected $signature = 'folder:search';

    protected $description = 'Procura por todas as pastas dentro do diretorio do servidor Messtechnik';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Informações de acesso ao servidor de arquivos compartilhados Samba, hospedado no servidor para
        // armazenar os dados que cada torre anemométrica envia através do protocolo SMTP
        $host = '192.168.1.251';
        $user = 'mstkSupEng';
        $workgroup = 'workgroup';
        $password = 'v20a40s60';
        $share = 'MMS';

//        $host = 'localhost';
//        $user = 'dani';
//        $workgroup = 'DANITESTE';
//        $password = 'ab159456';
//        $share = 'staradmin';

        // Inicializa uma instância de autenticação e de server (Biblioteca Icewind/SMB)
        $auth = new BasicAuth($user, $workgroup, $password);
        $serverFactory = new ServerFactory();
        $server = $serverFactory->createServer($host, $auth);
        $share = $server->getShare($share);

        // Para cada pasta de arquivos dentro do diretório MMS-MON (cada torre)
        foreach ($share->dir('/MMS-MON') as $torre) { // alterar path para /MMS-MON
            // Atribui um valor menor como data de alteração e busca pelo arquivo de dados com a maior data de alteração.
            // (ou seja, o último arquivo adicionado daquele cliente)
            $dataUltimaAlteracao = 0;
            echo $torre->getName().PHP_EOL;

            $log = new ArquivoLog([
                'nome' => '-',
                'diretorio' => $torre->getName()
            ]);

            if ($torre->isDirectory()) {
                foreach ($share->dir($torre->getPath()) as $arquivoLeitura) {
                    if (($arquivoLeitura->getMTime() > $dataUltimaAlteracao) &&
                        (substr($arquivoLeitura->getName(), 0, 4) === 'tb1_')) {
                        $leituraRecente = $arquivoLeitura;
                        $dataUltimaAlteracao = $arquivoLeitura->getMTime();
                    }
                }

                if (isset($leituraRecente)) {
                    $log->nome = $leituraRecente->getName();

                    $fileHandler = $share->read($leituraRecente->getPath());
                    $arquivoCabecalho = fgets($fileHandler);

                    $arquivoCabecalho = explode(",", $arquivoCabecalho)[1];
                    $arquivo = ArquivoLog::where('nome', $leituraRecente->getName())
                        ->where('status', 'success')
                        ->pluck('id')->first();
                    $torre = Tower::where('cod_arquivo_dados', str_ireplace('"', '', $arquivoCabecalho))->first();

                    if ($torre) {
                        if ($arquivo === null) {
                            $this->call('file:read', ['stream' => $fileHandler, 'torreId' => $torre->id]);
                            $log->status = 'success';
                            $log->mensagem = 'Dados armazenados com sucesso';
                            echo 'Salvando arquivos'.PHP_EOL;
                            fclose($fileHandler);
                            $torre->touch();
                        } else {
                            $log->status = 'warning';
                            $log->mensagem = 'Arquivo já foi lido e armazenado no banco de dados';
                            echo 'Arquivo já foi lido e armazenado no banco de dados'.PHP_EOL;
                        }
                    }
                    else {
                        $log->status = 'error';
                        $log->mensagem = 'Torre não encontrada. O código de arquivo não coincide com o encontrado no arquivo tb1_ do cliente';
                        echo "Torre não encontrada. O código de arquivo não coincide com o encontrado no arquivo tb1_ do cliente.".PHP_EOL;
                    }
                }
                else {
                    $log->status = 'warning';
                    $log->mensagem = 'Diretório não possui nenhum arquivo iniciado por tb1_';
                    echo 'Diretorio não possui nenhum arquivo iniciado por tb1_'.PHP_EOL;
                }
            }
            else {
                $log->status = 'warning';
                $log->mensagem = 'Arquivo lido não é um diretorio';
                echo 'Arquivo lido não é um diretorio'.PHP_EOL;
            }
            $leituraRecente = null;
            $log->save();
        }
    }
}