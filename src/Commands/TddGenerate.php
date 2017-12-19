<?php

namespace Ingenious\TddGenerate\Commands;

use Illuminate\Console\Command;
use Ingenious\TddGenerator\TddGenerator;

class TddGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:generate {model:The new model name} {--f|force : Overwrite existing files without confirmation}';

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
        $generator = TddGenerator::handle( $this->argument('model'), (bool) $this->option('force') );

        foreach( $generator->output as $comment ) {
            $this->comment($comment);
        }
    }
}
