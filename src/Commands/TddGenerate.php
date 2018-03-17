<?php

namespace Ingenious\TddGenerator\Commands;

use Illuminate\Console\Command;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Concerns\GetsUserInput;
use Ingenious\TddGenerator\Concerns\DisplaysOutput;

class TddGenerate extends Command
{
    use GetsUserInput, DisplaysOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:generate
        { model : The new model name }
        { parent? : The parent model }
        { prefix? : The route name prefix to use e.g. admin }
        { routes? : The routes file to use }
        { --force : Overwrite existing files without confirmation }
        { --backup : Backup and Replace existing fies }
        { --admin : Only allow admin access to the generated routes }
        { --defaults : Suppress prompts, use defaults }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new tdd stubs for the specified Model';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = ( new Params )
            ->setModel( $this->argument('model') )
            ->setParent( $this->getParent() )
            ->setPrefix( $this->getPrefix() )
            ->setRoutes( $this->getRoutesFile() )
            ->setAdmin( $this->getAdmin() )
            ->setForce( $this->getForce() )
            ->setBackup( $this->getBackup() );

        $this->output( Generator::handle( $params ) );

        $this->info("\nProcessing complete.");
    }
}
