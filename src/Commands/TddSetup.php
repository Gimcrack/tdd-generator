<?php

namespace Ingenious\TddGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Ingenious\TddGenerator\Managers\ChatManager;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Managers\SetupManager;
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
        { --tags= : The tags to include }
        { --prefix= : The route name prefix to use e.g. admin }
        { --routes= : The routes file to use }
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
     * @var  Params
     */
    private $params;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->params = ( new Params )
            ->setTags( $this->getTags() )
            ->setForce( $this->getForce() )
            ->setBackup( $this->getBackup() );

        if ( $this->params->hasTag('setup','any') )
        {
            $this->setup();
        }

        if ( $this->params->hasTag('admin','any') )
        {
            $this->admin();
        }

        if ( $this->params->hasTag('frontend','any') )
        {
            $this->frontend();
        }

        if ( $this->params->hasTag('chat','any') )
        {
            $this->chat();
        }

        if ( $this->params->hasTag('all','any') ) {
            if ( !! $this->ask("> Run tests? [No]", false) ) {
                $this->alert("Running Test Suite");
                $this->output( exec('./vendor/bin/phpunit --verbose --colors --debug --stop-on-failure -c phpunit.xml') );
            }

            if ( !! $this->ask("> Run migrations? [No]", false) ) {
                $this->alert("Migration database");
                $this->output( exec('php artisan migrate') );
            }
        }

        if ( $this->params->hasTag('frontend','any') ) {
            if ( !! $this->ask("> Compile assets? [No]", false) ) {
                $this->alert("Compiling assets");
                $this->output( exec('npm run dev') );
            }
        }
    }

    private function setup()
    {
        $this->alert("Setting up base files.");

        $this->output(
            SetupManager::base(),
            Generator::setup( $this->params )
        );
    }

    private function admin()
    {
        $this->alert("Setting up admin files.");

        $this->params
            ->setRoutes($this->getRoutesFile())
            ->setPrefix($this->getPrefix());

        Artisan::call('make:auth',['--no-interaction' => true]);

        $this->output(
            "Setting up Auth Scaffolding",
            SetupManager::admin(),
            Generator::admin($this->params)
        );
    }

    private function frontend()
    {
        $this->alert("Setting up frontend files.");

        $this->params
            ->setForce($this->getForce())
            ->setBackup($this->getBackup())
            ->setPrefix($this->getPrefix());

        $this->output(
            SetupManager::frontend(),
            Generator::frontend($this->params)
        );
    }

    private function chat()
    {
        $this->alert("Setting up chat files.");

        $this->output(
            Generator::chat($this->params),
            ChatManager::setup()
        );
    }
}
