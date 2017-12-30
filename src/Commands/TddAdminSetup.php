<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddParams;
use Ingenious\TddGenerator\TddGenerator;
use Ingenious\TddGenerator\TddSetupManager;

class TddAdminSetup extends Command
{
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
        $setup = new TddSetupManager();

        $setup->admin();

        foreach( $setup->output as $comment ) {
            $this->comment($comment);
        }

        $params = ( new TddParams )
            ->setRoutes( $this->getRoutesFile() )
            ->setPrefix( $this->getPrefix() )
            ->setForce( $this->getForce() );

        $generator = TddGenerator::admin( $params );

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

        if ( $this->option('defaults') ) {
            return File::name( $files[0] ) . ".php";
        }

        $this->comment("\n\nWhat routes files should the new routes be added to?");

        foreach( $files as $key => $file ) {
            $this->comment( " [{$key}] ". File::name($file) . ".php");
        }

        $chosen = $this->ask("> Select one. [0]") ?? 0;

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
}
