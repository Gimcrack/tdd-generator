<?php

namespace Ingenious\TddGenerator\Commands;

use Illuminate\Console\Command;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Managers\SetupManager;
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
        { --defaults : Suppress prompts, use defaults }
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
        $params = ( new Params )
            ->setForce( $this->getForce() )
            ->setBackup( $this->getBackup() );

        $this->output( SetupManager::frontend() );

        $this->output( Generator::frontend( $params ) );

        $this->info("\nProcessing complete.");
    }
}
