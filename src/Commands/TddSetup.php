<?php

namespace Ingenious\TddGenerator\Commands;

use Illuminate\Console\Command;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Generator;
use Illuminate\Support\Facades\Artisan;
use Ingenious\TddGenerator\Helpers\Npm;
use Ingenious\TddGenerator\Helpers\Composer;
use Ingenious\TddGenerator\Managers\ChatManager;
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
        { --admin : Admin only routes } 
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
        $this->params = ( new Params );

        if ( $this->shouldUseDefaults() )
        {
            $this->params->loadDefaults($this->defaults);
        }
        else
        {
            $this->params->setTags( $this->getTags() );
        }

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
            if ( $this->params->tests || $this->sanitizedAsk("> Run tests? [No]", false) ) {

                $phpunit = base_path('vendor/bin/phpunit --verbose --colors --debug --stop-on-failure -c phpunit.xml');
                $this->alert("Running Test Suite");
                $this->info("$phpunit");
                $this->output( shell_exec($phpunit) );
            }

            if ( $this->params->migrate || $this->sanitizedAsk("> Run migrations? [No]", false) ) {
                $this->alert("Migrating database");
                $this->output( shell_exec('php artisan migrate') );
            }
        }

        if ( $this->params->hasTag(['frontend','npm'],'any') ) {
            if ( $this->params->npm || $this->sanitizedAsk("> Install NPM Dependencies? [No]", false) ) {
                $this->alert("Installing NPM Dependencies");
                $this->output(Npm::install());
            }

            if ( $this->params->compile || $this->sanitizedAsk("> Compile assets? [No]", false) ) {
                $this->alert("Compiling assets");
                $this->output( shell_exec('npm run dev') );
            }
        }

        if ( $this->sanitizedAsk("> Cleanup Backups? [No]",false) ) {
            $this->call('tdd:cleanup-backups');
        }

        if ( $model = $this->sanitizedAsk("> Scaffold A Model? Enter the model name. [No]",false) ) {
            $this->call('tdd:generate',[ 'model' => $model]);
        }

        $this->alert("Processing Complete");
    }

    private function setup()
    {
        $this->alert("Setting up base files.");

        $this->params
            ->setForce( $this->getForce() )
            ->setBackup( $this->getBackup() );

        $this->output(
            SetupManager::base(),
            Composer::setup(),
            Generator::setup( $this->params )
        );
    }

    private function admin()
    {
        $this->alert("Setting up admin files.");

        $this->params
            ->setForce( $this->getForce() )
            ->setAdmin( $this->getAdmin() )
            ->setBackup( $this->getBackup() )
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
            Generator::frontend($this->params),
            SetupManager::frontend()
        );
    }

    private function chat()
    {
        $this->alert("Setting up chat files.");

        $this->params
            ->setForce($this->getForce())
            ->setBackup($this->getBackup());

        $this->output(
            Generator::chat($this->params),
            ChatManager::setup()
        );
    }


}
