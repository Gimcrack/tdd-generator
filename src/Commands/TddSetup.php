<?php

namespace Ingenious\TddGenerator\Commands;

use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddParams;
use Ingenious\TddGenerator\TddGenerator;
use Ingenious\TddGenerator\TddSetupManager;
use Ingenious\TddGenerator\Concerns\GetsUserInput;
use Ingenious\TddGenerator\Concerns\DisplaysOutput;

class TddSetup extends Command
{
    use GetsUserInput, DisplaysOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:setup
        { --force : Force overwriting of existing files }
        { --backup : Backup and Replace existing fies }
        { --defaults : Suppress prompts, use defaults }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial Setup for Tdd Generator';

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

        $this->output( TddSetupManager::base() );

        $this->output( TddGenerator::setup( $params ) );
    }
}
