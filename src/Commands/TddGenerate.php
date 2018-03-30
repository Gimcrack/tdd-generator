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
        { model : The new models to scaffold (comma-separated for multiple) }
        { --tags= : The tags to include }
        { --prefix= : The route name prefix to use e.g. admin }
        { --routes= : The routes file to use }
        { --force : Overwrite existing files without confirmation }
        { --backup : Backup and Replace existing fies }
        { --admin : Only allow admin access to the generated routes }
        { --defaults : Suppress prompts, use defaults }
        { --no-tests : Don\'t run tests }
        { --no-migrations : Don\'t run migrations }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new tdd stubs for the specified Model';

    /**
     * The Params
     *
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
        $models = explode(",",$this->argument('model'));

        $this->params = ( new Params );

        if ( $this->shouldUseDefaults() )
        {
            $this->params->loadDefaults($this->defaults);
        }
        else
        {
            $this->params
                ->setTags( $this->getTags() )
                //->setParent( $this->getParent() )
                ->setPrefix( $this->getPrefix() )
                ->setRoutes( $this->getRoutesFile() )
                ->setAdmin( $this->getAdmin() )
                ->setForce( $this->getForce() )
                ->setBackup( $this->getBackup() );
        }

        $this->alert("Beginning Processing");

        foreach( $models as $model )
        {
            $this->params->setModel($model);
            $this->output(
                "Scaffolding $model Model",
                Generator::handle( $this->params )
            );
        }

        if ( $this->params->hasTag('all','any') ) {
            if ( ! $this->option('no-tests') && ( $this->params->tests || $this->sanitizedAsk("> Run tests? [No]", false) ) ) {

                $phpunit = base_path('vendor/bin/phpunit --verbose --colors --debug --stop-on-failure -c phpunit.xml');
                $this->alert("Running Test Suite");
                $this->info("$phpunit");
                $this->output( shell_exec($phpunit) );
            }

            if ( ! $this->option('no-migrations') && ( $this->params->migrate || $this->sanitizedAsk("> Run migrations? [No]", false) ) ) {
                $this->alert("Migrating database");
                $this->output( shell_exec('php artisan migrate') );
            }
        }

        $this->alert("Processing Complete");
    }
}
