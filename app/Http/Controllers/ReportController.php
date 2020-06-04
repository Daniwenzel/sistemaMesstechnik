<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index() {
        return view('report.index');
    }

    // Busca pelo registro da torre dentro do banco de dados
    public function getTorreByEstacao(string $estacao) {
        $torre = DB::table('SITE')
        ->select('SITE.CODIGO', 'SITE.SITENAME', 'SITE.ESTACAO')
        ->where('SITE.ESTACAO','=',$estacao)
        ->get();

        return $torre->first() ? $torre->first() : false;
    }

    // Retorna uma lista com todas as torres cadastradas no banco. 
    public function getTorresList() {
        return DB::table('SITE')
    	->join('CLIENTE', 'SITE.CLICODIGO', '=', 'CLIENTE.CODIGO')
    	->select('SITE.CODIGO', 'SITE.SITENAME', 'SITE.ESTACAO', 'CLIENTE.RAZAOSOCIAL')
    	->orderBy('CLIENTE.RAZAOSOCIAL')
    	->get();
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

            return;
        }
        else {
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
                preg_match('#\((.*?)\)#', $this->getTorreByEstacao($arr[0])->SITENAME, $primeiraTorre);
                preg_match('#\((.*?)\)#', $this->getTorreByEstacao($arr[1])->SITENAME, $segundaTorre);

                $titulo = 'Correlação torres: '.$primeiraTorre[1].' e '.$segundaTorre[1];
            }
            else {
                preg_match('#\((.*?)\)#', $this->getTorreByEstacao($folder)->SITENAME, $primeiraTorre);
                $titulo = 'Torre '.$primeiraTorre[1];
            }
            
        	return view('report.plots', compact(['fullPlotsPath','titulo']));
        }
        // Se a pasta com os arquivos dos plots não existir, retorna 404
        abort(404);
    }

    // Retorna uma lista com todas as pastas dos plots (correlações ou torres) já gerados dentro do MMS
    public function list() {
        // scan dentro da pasta C:\xampp\htdocs\sistemaMesstechnik\public\images\plots retornando o nome dos diretórios presentes
        $files = preg_grep('/^([^.])/', scandir(getcwd().'/images/plots'));

        if (!empty($files)) {
            foreach($files as $file) {
            // Busca pelo nome das torres, havendo apenas o código presente no nome do diretório,
            // Ou atribui a string 'Torre inexistente' caso não seja possível encontrar uma torre com este código
                if (strpos($file, '-') !== false) {
                    $arr = explode('-', $file, 2);
                    preg_match('#\((.*?)\)#', $this->getTorreByEstacao($arr[0])->SITENAME ?? 
                    '(Torre inexistente)', $match);
                    $nomes[$file] = $match[1];
                    preg_match('#\((.*?)\)#', $this->getTorreByEstacao($arr[1])->SITENAME ?? 
                    '(Torre inexistente)', $match);
                    $nomes[$file] .= ' - '.$match[1];
                }
                else {
                    preg_match('#\((.*?)\)#', $this->getTorreByEstacao($file)->SITENAME ?? 
                    '(Torre inexistente)', $match);
                    $nomes[$file] = $match[1];
                }
            }
        }
        else {
            $nomes = [];
        }

        #dd($nomes);

        return view('report.list', compact(['files', 'nomes']));
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
            
            return;
        }
        else {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode("Codigos invalidos! Uma ou ambas as torres nao foram encontradas."));
        }
    }

    // Se houver um arquivo válido no request, armazena o arquivo dentro de "/storage/app/public/epe", e envia seu 'path' como parametro na chamada do scriptGenerateEpe.R
    public function generateEpe(Request $request) {
        $arq = $request->file('arquivoEpe');

        if ($arq->isValid()) {
            $nomeArquivo = $arq->getClientOriginalName();

            $upload = $arq->storeAs('epe', $nomeArquivo, 'public');

            if ($upload) {
                // Atenção para alterações no diretório do projeto!
                // Caso houver, modificar $cmd para atender as mudanças.
                $cmd = '"C:\Program Files\R\R-3.6.1\bin\Rscript.exe" C:\xampp\htdocs\sistemaMesstechnik\resources\rcode\scriptGenerateEpe.R '.$nomeArquivo." --vanilla 2>&1";

                $rawResponse = shell_exec($cmd);
                $response = explode("\n", $rawResponse);

                return redirect()->route('reports.plots', array('folder' => substr($nomeArquivo,0,6)))->with('message', $response);
            }
        }
        // Caso ocorra algo inexperado, fora do fluxo normal da funcionalidade, retorna erro
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
                // Caso houver, modificar $cmd para atender as mudanças.
                $cmd = '"C:\Program Files\R\R-3.6.1\bin\Rscript.exe" C:\xampp\htdocs\sistemaMesstechnik\resources\rcode\scriptCompareEpe.R '.$nomePrimeiroArq." ".$nomeSegundoArq." --vanilla 2>&1";

                $rawResponse = shell_exec($cmd);
                $response = explode("\n", $rawResponse);

                return redirect()->route('reports.plots', array('folder' => substr($nomePrimeiroArq,0,6).'-'.substr($nomeSegundoArq,0,6)))->with('message', $response);
            }
        }
        // Caso ocorra algo inexperado, fora do fluxo normal da funcionalidade, retorna erro
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode("Arquivo invalido! Não foi possivel salvar o arquivo."));
    }
}