<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddParams;
use Ingenious\TddGenerator\TddGenerator;
use Ingenious\TddGenerator\TddSetupManager;
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
        { --defaults : Supress prompts, use defaults }
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
        $params = ( new TddParams )
            ->setRoutes( $this->getRoutesFile() )
            ->setPrefix( $this->getPrefix() )
            ->setForce( $this->getForce() );

        $this->output( TddSetupManager::admin() );

        $this->output( TddGenerator::admin( $params ) );

        $this->info("\nProcessing complete.");
    }
}
