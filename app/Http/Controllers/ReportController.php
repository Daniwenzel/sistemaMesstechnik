<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Messtechnik\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
//use Messtechnik\Models\Site;
use Messtechnik\Traits\Logs;

class ReportController extends Controller
{
    use Logs;

    /*
        Atenção. O diretório do executavel do Rscript e dos scripts estão sendo passados de maneira estática. Alterar caso a instalação seja feita em outro caminho.
    */
    private $rscriptExe = '"C:\Program Files\R\R-4.2.2\bin\Rscript.exe"';
    private $scriptsPath = 'C:\xampp\htdocs\sistemaMesstechnik\resources\rcode';

    /*
        Mostra a view inicial dos relatórios
    */
    public function index() {
        return view('report.index');
    }

    /*
        Busca pelo registro da torre dentro do banco de dados
    */
    /*
    public function getTower(string $estacao) {
        $result = false;

        $torre = DB::table('SITES_FIREBIRD')
            ->select('CODIGO', 'SITENAME', 'ESTACAO')
            ->where('ESTACAO', $estacao)
            ->get();

        if ($torre->first()) {
            /*
                Atribui o conteúdo entre parênteses do SITENAME na variável "NOME"
            
            preg_match('#\((.*?)\)#', $torre->first()->SITENAME, $match);
            $torre->first()->NOME = $match[1];

            $result = $torre->first();
        }

        return $result;
    }
    */

    /*
        Retorna uma lista com todas as torres cadastradas no banco MySQL. 
    */
    public function getTowerList() {
        return DB::connection('mysql')->table('SITES_FIREBIRD')->join('CLIENTES', 'SITES_FIREBIRD.CLICODIGO', '=', 'CLIENTES.CODIGO')->select('SITES_FIREBIRD.CODIGO', 'SITES_FIREBIRD.SITENAME', 'SITES_FIREBIRD.ESTACAO', 'CLIENTES.RAZAOSOCIAL')->orderBy('CLIENTES.RAZAOSOCIAL')->get();
    }
    
    /*
        Gera um paginador, utilizado na lista de torres 
    */
    public function getPaginator(Request $request, $items) {
        $total = count($items);
        $page = $request->page ?? 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $items = array_slice($items, $offset, $perPage);

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query()
        ]);
    }

    /*
        Mostra a view "correlação", enviando uma lista das torres agrupadas por cliente
    */
    public function showCompare() {
    	//$torres = $this->getTowerList();

    	//$grouped = $torres->groupBy('RAZAOSOCIAL');
        //$grouped->toArray();
        
        return view('report.correlation');
    }

    /*
        Recebe o codigo estação de 2 torres e invoca o script Rcode que acessa o banco de dados e compara os dados das torres escolhidas. Criando imagens estáticas desta comparação dentro do diretorio da aplicação e retorna para a view mostrando as imagens criadas.
    */
    public function compare(Request $request) {
        $torreRef = $request->torreReferencia;
        $torreSec = $request->torreSecundaria;
        $periodo = $request->periodo;
        $diretorio = $request->diretorio;

        // Se encontrar o registro das torres, insere o código estação das torres e o período como parâmetros para a invocação do script Rcode
        if($torreRef && $torreSec) {
            // Atenção, o script possui credenciais de banco de dados
            $cmdCompare = $this->rscriptExe.' '.$this->scriptsPath.'\scriptCompare.R '.$torreRef." ".$torreSec." ".$periodo." --vanilla 2>&1";
            // getcwd() = /var/www/sistemaMesstechnik/public ou C:\xampp\htdocs\sistemaMesstechnik\public

            $rawResponse = shell_exec($cmdCompare);
            $response = explode("\n", $rawResponse);
            $request->session()->flash('message', $response);

            $this->createLog($diretorio,'success','Comparação das torres: '.$torreRef.' e '.$torreSec.' Criada com sucesso.');

            return;
        }
        else {
            $this->createLog($diretorio, "error", "Falha ao comparar torre(s): ".$torreRef.' e '.$torreSec);

            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode("Codigos invalidos! Uma ou ambas as torres nao foram encontradas."));
        }
    }

    /*
        Mostra os plots/imagens estáticas da torre ou correlação de torres
    */
    public function showPlots(string $folder) {
        // $folder será o nome da pasta que armazena as imagens estáticas criadas durante a comparação/geração. Caso seja uma comparação, o primeiro valor será o código estação maior. Ex: "000575-000574". Caso seja a geração de uma torre, $folder receberá apenas o código estação dela. Ex: "000575"

        $prefixo = "\images\plots\\".$folder."\\";

        // Verifica se existe arquivos dentro da pasta escolhida. Insere o nome das imagens/plots (com extensão jpeg, jpg e png, case INSENSITIVE), na array $imagens. Atenção, caso o diretório dos plots seja alterado, alterar a função getcwd para atender mudanças.
        if (file_exists(getcwd().$prefixo)) {
            $imagens = preg_grep('~\.(jpeg|jpg|png)$~i', scandir(getcwd().$prefixo));

            // Insere o prefixo necessário para atribuir o path correto dentro do parâmetro src da tag <img src={{asset(...)}}>
            $fullPlotsPath = substr_replace($imagens, $prefixo, 0, 0);
    
            // Se o nome da pasta tiver um hífen (correlação), 
            if (strpos($folder, '-') !== false) {
                $arr = explode('-', $folder, 2);

                //$titulo = 'Correlação torres: '.$primeiraTorre->NOME.' e '.$segundaTorre->NOME;
                $titulo = 'Correlação torres: '.$arr[0].' e '.$arr[1];
            }
            else {
                $titulo = 'Torre '. $folder;
            }
            
        	return view('report.plots', compact(['fullPlotsPath','titulo']));
        }
        // Se a pasta com os arquivos dos plots não existir, retorna 404
        abort(404);
    }

    // Retorna uma lista com todas as pastas dos plots (correlações ou torres) já gerados dentro do MMS
    public function showTorresList(Request $request) {
        // scan dentro da pasta C:\xampp\htdocs\sistemaMesstechnik\public\images\plots retornando o nome dos diretórios presentes

        $pastas = preg_grep('/^([^.])/', scandir(getcwd().'/images/plots'));

        foreach ($pastas as $pasta) {
            $datas[] = date("F d Y H:i:s.", filemtime(getcwd().'/images/plots'.'/'.$pasta));
        }

        $lista = array_map(function ($pastas, $datas) {
            return [
                'nome' => $pastas,
                'data' => $datas,
            ];
        }, $pastas, $datas);
        

        //$listaPlots = array();
        //$listaPlots['pastas'] = preg_grep('/^([^.])/', scandir(getcwd().'/public/images/plots'));

        //$pastas = preg_grep('/^([^.])/', scandir(getcwd().'/public/images/plots'));

        if (!empty($pastas)) {
            foreach($pastas as $pasta) {
            // Busca pelo nome das torres, havendo apenas o código presente no nome do diretório,
            // Ou atribui a string 'Torre inexistente' caso não seja possível encontrar uma torre com este código
                if (strpos($pasta, '-') !== false) {
                    $arr = explode('-', $pasta, 2);

                    $nome = $arr[0].' - '.$arr[1];
                }
                else {
                    $nome = $pasta;
                }
                if (is_null($request->search)) {
                    $nomes[$pasta] = $nome;
                }
                elseif (stripos($pasta, $request->search) !== false) {
                    $nomes[$pasta] = $nome;
                }
            }
        }
        if (!isset($nomes)) {
            $nomes = [];
        }

        $paginator = $this->getPaginator($request, $nomes);

        return view('report.list')->with('nomes', $paginator);
    }

    public function showGenerate() {
        //$torres = $this->getTowerList();

    	//$grouped = $torres->groupBy('RAZAOSOCIAL')->toArray();
        // $grouped->toArray();

        //return view('report.generate', compact('torres','grouped'));
        return view('report.generate');
    }

    public function generate(Request $request) {
        $torreUm = $request->primeiraTorre;

        if($torreUm) {
             $cmdGenerate = $this->rscriptExe.' '.$this->scriptsPath.'\scriptGenerate.R '.$torreUm." ".$request->periodo.' --vanilla 2>&1';

            $rawResponse = shell_exec($cmdGenerate);
            $response = explode("\n", $rawResponse);
            $request->session()->flash('message', $response);

            $this->createLog($request->diretorio,'success','Geração dos plots da torre '.$torreUm.' Criada com sucesso.');
            
            return;
        }
        else {
            $this->createLog($request->diretorio,'error', 'Falha ao encontrar torre com código estação = '.$torreUm);

            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode("Codigos invalidos! Uma ou ambas as torres nao foram encontradas."));
        }
    }

    // Se houver um arquivo válido no request, armazena o arquivo dentro de "/storage/app/public/epe", e envia seu 'path' como parametro na chamada do scriptGenerateEpe.R
    public function generateEpe(Request $request) {
        $arquivo = $request->file('arquivoEpe');

        if ($arquivo->isValid()) {
            $nomeArquivo = $arquivo->getClientOriginalName();

            $upload = $arquivo->storeAs('epe', $nomeArquivo, 'public');

            if ($upload) {
     
                $cmdGenerateEpe = $this->rscriptExe.' '.$this->scriptsPath.'\scriptGenerateEpe.R '.$nomeArquivo." --vanilla 2>&1";

                $rawResponse = shell_exec($cmdGenerateEpe);
                $response = explode("\n", $rawResponse);

                $folder = substr($nomeArquivo,0,6);

                $this->createLog($folder,'success','Geração arquivo EPE: '.$folder.' realizada com sucesso.');

                // Chama a função showPlots enviando a pasta (codigo estacao) como parametro, e as mensagens pela session
                return redirect()->route('reports.plots', array('folder' => $folder))->with('message', $response);
            }
        }
        // Caso ocorra algo inesperado, fora do fluxo normal da funcionalidade, retorna erro
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode("Arquivo invalido! Não foi possivel salvar o arquivo.")); 
    }

    public function compareEpe(Request $request) {
        $primeiroArq = $request->file('primeiroEpe');
        $segundoArq = $request->file('segundoEpe');

        if ($primeiroArq->isValid() && $segundoArq->isValid()) {
        //if ($request->hasFile('arquivoEpe') && $request->file('arquivoEpe')->isValid()) {
            //$file = $request->file('arquivoEpe');
            $nomePrimeiroArq = $primeiroArq->getClientOriginalName();
            $nomeSegundoArq = $segundoArq->getClientOriginalName();

            $primeiroUpload = $primeiroArq->storeAs('epe', $nomePrimeiroArq, 'public');
            $segundoUpload = $segundoArq->storeAs('epe', $nomeSegundoArq, 'public');

            if ($primeiroUpload && $segundoUpload) {
                $cmdCompareEpe = $this->rscriptExe.' '.$this->scriptsPath.'\scriptCompareEpe.R '.$nomePrimeiroArq." ".$nomeSegundoArq." --vanilla 2>&1";

                $rawResponse = shell_exec($cmdCompareEpe);
                $response = explode("\n", $rawResponse);

                $estacaoMaior = max(substr($nomePrimeiroArq, 0, 6), substr($nomeSegundoArq, 0, 6));
                $estacaoMenor = min(substr($nomePrimeiroArq, 0, 6), substr($nomeSegundoArq, 0, 6));

                $folder = $estacaoMaior.'-'.$estacaoMenor;

                $this->createLog($folder,'success','Comparação arquivos EPE: '.$estacaoMaior.' e '.$estacaoMenor.' realizada com sucesso.');

                return redirect()->route('reports.plots', array('folder' => $folder))->with('message', $response);
            }
        }
        // Caso ocorra algo inesperado, retorna erro
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode("Arquivo invalido! Não foi possivel salvar o arquivo."));
    }

    public function generateCsv(Request $request) {
        $file="C:\Users\adminpclnv03\Documents\dados.csv";
        $csv= file_get_contents($file);
        $array = array_map("str_getcsv", explode("\n", $csv));
        // $json = json_encode($array);
        // print_r($json);
        
        return response()->json($array);
    }

}