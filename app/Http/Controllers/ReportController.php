<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Messtechnik\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Messtechnik\Models\Site;
use Messtechnik\Traits\Logs;

class ReportController extends Controller
{
    use Logs;

    public function index() {
        return view('report.index');
    }

    // Busca pelo registro da torre dentro do banco de dados
    public function getTorreByEstacao(string $estacao) {
        $result = false;

        $torre = DB::connection('mysql')->table('SITE')
        ->select('SITE.CODIGO', 'SITE.SITENAME', 'SITE.ESTACAO')
        ->where('SITE.ESTACAO','=',$estacao)
        ->get();

        if ($torre->first()) {
            preg_match('#\((.*?)\)#', $torre->first()->SITENAME, $match);
            $torre->first()->NOME = $match[1];

            $result = $torre->first();
        }

        return $result;
    }

    // Retorna uma lista com todas as torres cadastradas no banco. 
    public function getTorresList() {
        return DB::connection('mysql')->table('SITE')->join('CLIENTE', 'SITE.CLICODIGO', '=', 'CLIENTE.CODIGO')->select('SITE.CODIGO', 'SITE.SITENAME', 'SITE.ESTACAO', 'CLIENTE.RAZAOSOCIAL')->orderBy('CLIENTE.RAZAOSOCIAL')->get();
    }

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

    // Mostra a view "correlação", enviando uma lista das torres, organizadas pelo cliente
    public function showCompare() {
    	$torres = $this->getTorresList();

    	$grouped = $torres->groupBy('RAZAOSOCIAL');
        $grouped->toArray();
        
        return view('report.correlation', compact('torres','grouped'));
    }

    public function compare(Request $request) {
        $torreRef = $this->getTorreByEstacao($request->torreReferencia);
        $torreSec = $this->getTorreByEstacao($request->torreSecundaria);

        // Se encontrar o registro das torres, formata o $cmd a ser executado, utilizando o código estação das torres e o período como parâmetros da chamada do scriptCompare.R
        if($torreRef && $torreSec) {
            // Checar alterações no diretório do projeto, mover script para a pasta public caso não
            // for uma "quebra" de segurança. Usar função getcwd() se script estiver na public.
            $cmd = '"C:\Program Files\R\R-3.6.1\bin\Rscript.exe" C:\xampp\htdocs\sistemaMesstechnik\resources\rcode\scriptCompare.R '.
            $torreRef->ESTACAO." ".$torreSec->ESTACAO." ".$request->periodo." --vanilla 2>&1";
            // getcwd() = /var/www/sistemaMesstechnik/public ou C:\xampp\htdocs\sistemaMesstechnik\public

            $rawResponse = shell_exec($cmd);
            $response = explode("\n", $rawResponse);
            $request->session()->flash('message', $response);

            $this->createLog($request->diretorio,'success','Comparação das torres: '.$torreRef->NOME.' e '.$torreSec->NOME.' Criada com sucesso.');

            return;
        }
        else {
            $this->createLog($request->diretorio, "error", "Falha ao encontrar torre(s): ".$request->torreReferencia." = ".($torreRef->NOME ?? 'Inexistente').' e '.$request->torreSecundaria." = ".($torreSec->NOME ?? 'Inexistente'));

            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode("Codigos invalidos! Uma ou ambas as torres nao foram encontradas."));
        }
    }

    // Mostra os plots/imagens da torre ou correlação de torres
    public function showPlots(string $folder) {
        $prefixo = "\images\plots\\".$folder."\\";

        // Adiciona o nome das imagens/plots (com extensão jpeg,jpg e png, case INSENSITIVE),
        // da pasta escolhida, para a array $imagens
        // Atenção, caso o diretório dos plots for alterado, alterar getcwd para atender mudanças.
        if (file_exists(getcwd().$prefixo)) {
            $imagens = preg_grep('~\.(jpeg|jpg|png)$~i', scandir(getcwd().$prefixo));

            // Insere o prefixo necessário para atribuir o path correto dentro do parâmetro src da tag <img src={{asset(...)}}>
            $fullPlotsPath = substr_replace($imagens, $prefixo, 0, 0);
    
            if (strpos($folder, '-') !== false) {
                $arr = explode('-', $folder, 2);
                $primeiraTorre = $this->getTorreByEstacao($arr[0]); 
                $segundaTorre = $this->getTorreByEstacao($arr[1]);

                //$titulo = 'Correlação torres: '.$primeiraTorre->NOME.' e '.$segundaTorre->NOME;
                $titulo = $primeiraTorre && $segundaTorre ? 'Correlação torres: '.$primeiraTorre->NOME.' e '.$segundaTorre->NOME : 'Correlação torres: '.$arr[0].' e '.$arr[1];
            }
            else {
                $primeiraTorre = $this->getTorreByEstacao($folder);

               // $titulo = 'Torre '.$primeiraTorre->NOME;
                $titulo = $primeiraTorre ? 'Torre '.$primeiraTorre->NOME : 'Torre '.$folder;
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

        if (!empty($pastas)) {
            foreach($pastas as $pasta) {
            // Busca pelo nome das torres, havendo apenas o código presente no nome do diretório,
            // Ou atribui a string 'Torre inexistente' caso não seja possível encontrar uma torre com este código
                if (strpos($pasta, '-') !== false) {
                    $arr = explode('-', $pasta, 2);

                    $nome = ($this->getTorreByEstacao($arr[0])->NOME ?? 'Torre Inexistente').' - '.($this->getTorreByEstacao($arr[1])->NOME ?? 'Torre Inexistente');
                }
                else {
                    $nome = $this->getTorreByEstacao($pasta)->NOME ?? 'Torre Inexistente';
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
        $torres = $this->getTorresList();

    	$grouped = $torres->groupBy('RAZAOSOCIAL')->toArray();
        // $grouped->toArray();

        return view('report.generate', compact('torres','grouped'));
    }

    public function generate(Request $request) {
        $torreUm = $this->getTorreByEstacao($request->primeiraTorre);

        if($torreUm) {
            // Atenção para alterações no diretório do projeto!
            // Caso houver, modificar $cmd para atender as mudanças.
            // Caso o script estiver na pasta public, pesquisar possiveis falhas de segurança. Utilizar função getcwd() = /var/www/sistemaMesstechnik/public no linux ou C:\xampp\htdocs\sistemaMesstechnik\public no windows

            $cmd = '"C:\Program Files\R\R-3.6.1\bin\Rscript.exe" C:\xampp\htdocs\sistemaMesstechnik\resources\rcode\scriptGenerate.R '.$torreUm->ESTACAO." ".$request->periodo.' --vanilla 2>&1';

            $rawResponse = shell_exec($cmd);
            $response = explode("\n", $rawResponse);
            $request->session()->flash('message', $response);

            $this->createLog($request->diretorio,'success','Geração dos plots da torre '.$torreUm->NOME.' Criada com sucesso.');
            
            return;
        }
        else {
            $this->createLog($request->diretorio,'error', 'Falha ao encontrar torre com código estação = '.$request->primeiraTorre);

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
                // Atenção para alterações no diretório do projeto!
                // Caso houver, modificar $cmd para atender as mudanças.
                $cmd = '"C:\Program Files\R\R-3.6.1\bin\Rscript.exe" C:\xampp\htdocs\sistemaMesstechnik\resources\rcode\scriptGenerateEpe.R '.$nomeArquivo." --vanilla 2>&1";

                $rawResponse = shell_exec($cmd);
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
                // Atenção para alterações no diretório do projeto!
                // Caso houver, modificar a string $cmd para atender as mudanças.
                $cmd = '"C:\Program Files\R\R-3.6.1\bin\Rscript.exe" C:\xampp\htdocs\sistemaMesstechnik\resources\rcode\scriptCompareEpe.R '.$nomePrimeiroArq." ".$nomeSegundoArq." --vanilla 2>&1";

                $rawResponse = shell_exec($cmd);
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