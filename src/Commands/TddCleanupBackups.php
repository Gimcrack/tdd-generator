<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddGenerator;
use Ingenious\TddGenerator\TddSetupManager;

class TddCleanupBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:cleanup-backups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup backup files';

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
        $files = $this->rglob("*.bak");

        if ( ! count($files) ) {
            $this->info("Nothing to clean up");
            return;
        }

        $this->info("\nCleaning up " . count($files) . " files.\n");

        $this->info("Delete or move .bak files?");
        $this->comment("> 1. Move");
        $this->comment("> 2. Delete");
        $which = $this->ask("> Which?",1);

        if ( $which == 1 ) {
            $backups = base_path("backups");
            if ( ! file_exists( $backups ) ) {
                mkdir( $backups );
            }

            foreach($files as $file) {
                File::move($file,  $backups . DIRECTORY_SEPARATOR . File::name($file) . ".bak" );
                $this->comment("Moving $file... Done.");
            }
            return;
        }

        elseif ( $which == 2 ) {
            foreach($files as $file) {
                File::delete($file);
                $this->comment("Deleting $file... Done.");
            }
            return;
        }

        return $this->handle();
    }

    /**
     * Recursive glob
     * @method rglob
     *
     * @return   array
     */
    public function rglob($pattern)
    {
        $files = [];

        for( $ii = 0; $ii<=10; $ii++ ) {
            $pat = base_path() . str_repeat( DIRECTORY_SEPARATOR . "*" , $ii) . DIRECTORY_SEPARATOR . $pattern;
            $files = array_merge($files, File::glob($pat));
        }

        return $files;
    }
}
