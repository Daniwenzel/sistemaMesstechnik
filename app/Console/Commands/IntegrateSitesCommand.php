<?php

namespace Messtechnik\Console\Commands;

use Illuminate\Console\Command;
use Messtechnik\IntegrateSites;

class IntegrateSitesCommand extends Command
{
    private $integration;

    protected $signature = 'integrate:sites';

    protected $description = 'Integrar sites do banco de dados firebird para o mysql';

    public function __construct(IntegrateSites $integration)
    {
        parent::__construct();
        $this->integration = $integration;
    }

    public function handle()
    {
        $this->integration->integrarSites();
    }
}
