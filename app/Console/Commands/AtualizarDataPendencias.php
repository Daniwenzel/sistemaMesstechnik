<?php

namespace Messtechnik\Console\Commands;

use Illuminate\Console\Command;
use Messtechnik\Models\Pendencia;
use Messtechnik\Models\Equipamento;
use Messtechnik\Traits\Pendencias;


class AtualizarDataPendencias extends Command
{
    use Pendencias;

    protected $signature = 'pendencias:atualizar';

    protected $description = 'Verifica se a data de substituicao do equipamento aproximou-se de 365 ou 90 dias, e atualiza a gravidade da pendencia em casos positivos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        /*
            Busca por todos os registros de equipamentos no banco de dados, e verifica um por um
        */
        $equipamentos = Equipamento::all();

        foreach($equipamentos as $equipamento) {
            /*
                Se o estado do equipamento for Irregular, busca pela pendência ou cria uma nova pendência para o equipamento.

                atualizarPendencia($equipcodigo, $oemcodigo, $descricao, $gravidade, $status)
            */
            if($equipamento->estado === 'Irregular') {

                $descricaoPendencia = 'Pendência gerada pois equipamento "'.$equipamento->descricao.'" está "Irregular".';

                $this->atualizarPendencia($equipamento->codigo, $equipamento->oemcodigo, $descricaoPendencia, 'Urgente', 'Pendente');
            } 

            elseif($equipamento->data_substituicao) {
                $diasParaSubstituicao = date_diff(date_create(date('Y-m-d')), date_create($equipamento->data_substituicao))->format('%R%a');
            
                /*
                    Se o equipamento não for Irregular, mas tiver uma data de substituição, verifica se a data de substituição já ocorreu, e caso positivo, modifica o estado do equipamento para "Irregular" e cria/atualiza sua pendência.
                */
                if($diasParaSubstituicao <= 0) {
                    $equipamento->update(['estado' => 'Irregular']);
                    $descricaoPendencia = 'Pendência gerada pois equipamento "'.$equipamento->descricao.'" está com a substituição atrasada.';

                    $this->atualizarPendencia($equipamento->codigo, $equipamento->oemcodigo, $descricaoPendencia, 'Urgente', 'Pendente');
                } 
                /*
                    Se a data de substituição não ocorreu, mas está próxima de até 90 dias, cria/atualiza a pendência.
                 */
                elseif($diasParaSubstituicao <= 90) {
                    $descricaoPendencia = 'Pendência gerada pois equipamento "'.$equipamento->descricao.'" está com a data de substituição próxima ('.$diasParaSubstituicao.' dias)';

                    $this->atualizarPendencia($equipamento->codigo, $equipamento->oemcodigo, $descricaoPendencia, 'Urgente', 'Pendente');
                } 
                /*
                    Se a data de substituição não ocorreu, mas está próxima de até 365 dias, cria/atualiza a pendência.
                */
                elseif($diasParaSubstituicao <= 365) {
                    $descricaoPendencia = 'Pendência gerada pois equipamento "'.$equipamento->descricao.'" está com a data de substituição próxima de 1 ano.';

                    $this->atualizarPendencia($equipamento->codigo, $equipamento->oemcodigo, $descricaoPendencia, 'Normal', 'Pendente');
                }
            }
            /*
                Se o equipamento não for Irregular e não tiver data de substituição, verifica se o equipamento possui pendência, e caso tiver, altera o estado da pendência para Realizado
            */
            elseif($pendencia = Pendencia::where('equipcodigo', $equipamento->codigo)->first()) {
                $pendencia->update(['status' => 'Realizado']);
            }
        }
    }
}