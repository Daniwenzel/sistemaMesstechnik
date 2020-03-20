<?php

namespace Messtechnik\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index() {
        return view('report.index');
    }

    public function showCompare() {
    	$torres = DB::table('SITE')
    	->join('CLIENTE', 'SITE.CLICODIGO', '=', 'CLIENTE.CODIGO')
    	->select('SITE.CODIGO', 'SITE.SITENAME', 'SITE.ESTACAO', 'CLIENTE.RAZAOSOCIAL')
    	->orderBy('CLIENTE.RAZAOSOCIAL')
    	->get();

    	$grouped = $torres->groupBy('RAZAOSOCIAL');
        $grouped->toArray();

        // dd($torres);

        return view('report.correlation', compact('torres','grouped'));
    }

    protected function getTorreByEstacao(string $estacao) {
        $torre = DB::table('SITE')
                    ->select('SITE.CODIGO', 'SITE.SITENAME', 'SITE.ESTACAO')
                    ->where('SITE.ESTACAO','=',$estacao)
                    ->get();
        return $torre;
    }

    public function compare(Request $request) {
        $torreUm = $this->getTorreByEstacao($request->primeiraTorre);
        $torreDois = $this->getTorreByEstacao($request->segundaTorre);
     
        if($torreUm->first() && $torreDois->first()) {
            // Checar alterações no diretório do projeto, mover script para a pasta public caso não
            // for uma quebra de *segurança*. Usar função getcwd() se script estiver na public.
            $cmd = "Rscript /var/www/sistemaMesstechnik/resources/rcode/scripts/script.R ".
            $torreUm->first()->ESTACAO." ".$torreDois->first()->ESTACAO." 2>&1";

            // $cmd = "Rscript ".getcwd()."/rcode/scripts/script.R ".
            // $torreUm->ESTACAO." ".$torreDois->ESTACAO." 2>&1"; // getcwd() retorna /var/www/sistemaMesstechnik/public

            return json_encode(shell_exec($cmd));
        }
        else {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode("Codigos invalidos! Uma ou ambas as torres nao foram encontradas."));
        }
    }

    // Mostra os plots/imagens da torre ou correlação de torres
    public function showPlots(string $folder) {
        $prefixo = "/images/plots/".$folder."/";

        // Adiciona o nome das imagens/plots (com extensão jpeg,jpg e png, case INSENSITIVE),
        // da pasta escolhida, para a array $imagens
        $imagens = preg_grep('~\.(jpeg|jpg|png)$~i', scandir(getcwd().$prefixo));
        
        // Insere o prefixo necessário para atribuir o asset dentro da tag <img src={{asset(...)}}>
        $fullPlotsPath = preg_filter('/^/',$prefixo, $imagens);

    	return view('report.plots', compact('fullPlotsPath'));
    }

    // Mostra uma lista de todas as pastas (correlações ou torres) de plots já geradas dentro do MMS
    public function list() {
        // scan dentro da pasta /public/images/plots retornando o nome dos diretórios presentes
        $files = preg_grep('/^([^.])/', scandir(getcwd().'/images/plots'));

        foreach($files as $file) {
            // Busca pelo nome das torres, havendo apenas o código presente no nome do diretório,
            // Ou atribui a string 'Torre inexistente' caso não seja possível encontrar uma torre com este código
            if (strpos($file, '-') !== false) {
                $arr = explode('-', $file, 2);
                preg_match('#\((.*?)\)#', $this->getTorreByEstacao($arr[0])->first()->SITENAME ?? 
                '(Torre inexistente)', $match);
                $nomes[$file] = $match[1];
                preg_match('#\((.*?)\)#', $this->getTorreByEstacao($arr[1])->first()->SITENAME ?? 
                '(Torre inexistente)', $match);
                $nomes[$file] .= ' - '.$match[1];
            }
            else {
                preg_match('#\((.*?)\)#', $this->getTorreByEstacao($file)->first()->SITENAME ?? 
                '(Torre inexistente)', $match);
                $nomes[$file] = $match[1];
            }
        }
        return view('report.list', compact(['files', 'nomes']));
    }
}