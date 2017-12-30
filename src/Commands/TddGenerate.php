<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddParams;
use Symfony\Component\Process\Process;
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
        { parent? : The parent model }
        { routes? : The routes file to use }
        { prefix? : The route name prefix to use e.g. admin }
        { --force : Overwrite existing files without confirmation }
        { --admin : Only allow admin access to the generated routes }
        { --defaults : Supress prompts, use defaults }
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
        $params = ( new TddParams )
            ->setModel( $this->argument('model') )
            ->setParent( $this->getParent() )
            ->setPrefix( $this->getPrefix() )
            ->setRoutes( $this->getRoutesFile() )
            ->setAdmin( $this->getAdmin() )
            ->setForce( $this->getForce() );

        $generator = TddGenerator::handle( $params );

        foreach( $generator->output as $comment ) {
            $this->comment($comment);
        }

        $this->info("\nProcessing complete.");
    }

    /**
     * Get the routes file
     * @method getRoutesFile
     *
     * @return   string
     */
    private function getRoutesFile()
    {
        if ( !! $this->argument('routes') ) {
            $routes = $this->argument('routes');

            $files = File::glob( base_path("routes" . DIRECTORY_SEPARATOR . "*{$routes}*" ) );

            if ( count($files) )
                return File::name($files[0]) . ".php";

            else
                throw new \Exception("Could not find routes file matching {$routes}");
        }

        $files = File::glob( base_path("routes" . DIRECTORY_SEPARATOR . "*api*.php" ) );

        if ( count($files) == 1 )
            return File::name($files[0]) . ".php";

        if ( $this->option('defaults') ) {
            return File::name( $files[0] ) . ".php";
        }

        $this->comment("\n\nWhat routes files should the new routes be added to?");

        foreach( $files as $key => $file ) {
            $this->comment( " [{$key}] ". File::name($file) . ".php");
        }

        $chosen = $this->ask("> Select one", 0);

        return File::name( $files[$chosen] ) . ".php";
    }

    /**
     * Get the route prefix
     * @method getPrefix
     *
     * @return   string
     */
    private function getPrefix()
    {
        if ( !! $this->argument('prefix') )
            return $this->argument('prefix');

        if ( $this->option('defaults') )
            return null;

        $this->comment("\n\nWhat prefix should the new routes have? Optional");

        return $this->ask("> Enter a prefix", false);
    }

    /**
     * Get the model Parent
     * @method getParent
     *
     * @return   string
     */
    private function getParent()
    {
        if ( !! $this->argument('parent') )
            return $this->argument('parent');

        if ( $this->option('defaults') )
            return null;

        $this->comment("\n\nDoes the model have a parent model? Optional");

        return $this->ask("> Enter a parent", false);
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
     * Admin only routes?
     * @method getForce
     *
     * @return   bool
     */
    private function getAdmin()
    {
        if ( !! $this->option('admin') )
            return true;

        if ( $this->option('defaults') )
            return false;

        return (bool) $this->ask("> Admin only routes?", false);
    }
}
