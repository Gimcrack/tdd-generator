<?php

namespace Ingenious\TddGenerator\Commands;

use Illuminate\Console\Command;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Concerns\GetsUserInput;
use Ingenious\TddGenerator\Concerns\DisplaysOutput;

class TddParent extends Command
{
    use GetsUserInput, DisplaysOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tdd:parent
        { parent : The parent model }
        { model : The base model }
        { --defaults : Use default options }
        { --prefix= : The route name prefix to use e.g. admin }
        { --routes= : The routes file to use }
        { --force : Overwrite existing files without confirmation }
        { --m2m : Many-to-many relationship, default is many-to-one }
        { --admin : Make the routes admin routes }
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new parent stubs for the specified Model';

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
        $this->params = ( new Params )
            ->setModel( $this->argument('model'))
            ->setParent( $this->argument('parent') )
            ->setForce( $this->getForce() )
            ->setAdmin( $this->getAdmin() )
            ->setRoutes( $this->getRoutesFile() )
            ->setPrefix( $this->getPrefix() )
            ->setM2M( $this->option('m2m') );

        $this->alert("Beginning Processing");

        if ( ! $this->params->m2m ) {
            $this->alert("Processing Many-To-One Relationship");
            Generator::parent( $this->params );
        }
        else {
            $this->alert("Processing Many-To-Many Relationship");
            Generator::m2m( $this->params );
        }

        $this->alert("Processing Complete");
    }
}
