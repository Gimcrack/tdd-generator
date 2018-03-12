<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddParams;
use Ingenious\TddGenerator\TddGenerator;
use Ingenious\TddGenerator\TddSetupManager;

class TddFrontendSetup extends Command
{
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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

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

        $generator = TddGenerator::frontend( $params );

        foreach( $generator->output as $comment ) {
            $this->comment($comment);
        }

        $setup = new TddSetupManager();

        $setup->frontend($this);

        foreach( $setup->output as $comment ) {
            $this->comment($comment);
        }
    }

    /**
     * Force overwriting of existing files?
     * @method getForce
     *
     * @return   bool
     */
    private function getForce()
    {
        if ( !! $this->option('force') )
            return true;

        if ( $this->option('defaults') )
            return false;

        return (bool) $this->ask("> Force overwriting of existing files?", false);
    }

    /**
     * Backup existing files?
     * @method getBackup
     *
     * @return   bool
     */
    private function getBackup()
    {
        if ( !! $this->option('backup') )
            return true;

        if ( !! $this->option('force') )
            return false;

        if ( $this->option('defaults') )
            return false;

        return (bool) $this->ask("> Skip or Backup/Replace existing files? [Skip]", false);
    }
}
