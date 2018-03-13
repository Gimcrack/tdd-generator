<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddParams;
use Ingenious\TddGenerator\TddGenerator;
use Ingenious\TddGenerator\TddSetupManager;
use Ingenious\TddGenerator\Concerns\GetsUserInput;
use Ingenious\TddGenerator\Concerns\DisplaysOutput;

class TddFrontendSetup extends Command
{
    use GetsUserInput, DisplaysOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:frontend-setup
        { --force : Force overwriting of existing files }
        { --backup : Backup existing files }
        { --defaults : Supress prompts, use defaults }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup frontend files and npm dependencies.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = ( new TddParams )
            ->setForce( $this->getForce() )
            ->setBackup( $this->getBackup() );

        $this->output( TddSetupManager::frontend() );

        $this->output( TddGenerator::frontend( $params ) );

        $this->info("\nProcessing complete.");
    }
}
