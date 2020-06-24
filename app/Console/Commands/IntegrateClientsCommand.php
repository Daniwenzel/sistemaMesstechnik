<?php

namespace Messtechnik\Console\Commands;

use Illuminate\Console\Command;
use Messtechnik\IntegrateClients;

class IntegrateClientsCommand extends Command
{
    private $integration;

    protected $signature = 'integrate:clients';

    protected $description = 'Integrar clientes do banco de dados firebird para o mysql';

    public function __construct(IntegrateClients $integration)
    {
        parent::__construct();
        $this->integration = $integration;
    }

    public function handle()
    {
        $this->integration->integrarClientes();
    }
}
