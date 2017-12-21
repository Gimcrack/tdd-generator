<?php

namespace Ingenious\TddGenerator\Commands;

use File;
use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddGenerator;
use Ingenious\TddGenerator\TddSetupManager;

class TddSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial Setup for Tdd Generator';

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

        $setup->process();

        foreach( $setup->output as $comment ) {
            $this->comment($comment);
        }
    }
}
