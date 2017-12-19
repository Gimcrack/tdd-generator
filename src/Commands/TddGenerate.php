<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddGenerator;

class TddGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:generate
        { model : The new model name }
        { routes? : The routes file to use }
        { --force : Overwrite existing files without confirmation }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new tdd stubs for the specified Model';

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
        $generator = TddGenerator::handle(
            $this->argument('model'),
            (bool) $this->option('force'),
            $this->getRoutesFile()
        );

        foreach( $generator->output as $comment ) {
            $this->comment($comment);
        }
    }

    /**
     * Get the routes file
     * @method getRoutesFile
     *
     * @return   string
     */
    private function getRoutesFile()
    {
        if ( !! $this->argument('routes') )
            return $this->argument('routes');

        $files = File::glob( base_path("routes" . DIRECTORY_SEPARATOR . "*api*.php" ) );

        if ( count($files) == 1 )
            return File::name($files[0]) . ".php";

        $this->comment("\n\nWhat routes files should the new routes be added to?");

        foreach( $files as $key => $file ) {
            $this->comment( " [{$key}] ". File::name($file) . ".php");
        }

        $chosen = $this->ask("> Select one. [0]") ?? 0;

        return File::name( $files[$chosen] ) . ".php";
    }
}
