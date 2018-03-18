<?php

namespace Ingenious\TddGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Managers\SetupManager;
use Ingenious\TddGenerator\Concerns\GetsUserInput;
use Ingenious\TddGenerator\Concerns\DisplaysOutput;

class TddAdminSetup extends Command
{
    use GetsUserInput, DisplaysOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:admin-setup
        { prefix? : The route name prefix to use e.g. admin }
        { routes? : The routes file to use }
        { --force : Force overwriting of existing files }
        { --defaults : Suppress prompts, use defaults }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup api-admin routes file and admin middleware.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = ( new Params )
            ->setRoutes( $this->getRoutesFile() )
            ->setPrefix( $this->getPrefix() )
            ->setForce( $this->getForce() );

        $this->info( Artisan::call("make:auth") );

        $this->output( SetupManager::admin() );

        $this->output( Generator::admin( $params ) );

        $this->info("\nProcessing complete.");
    }
}
