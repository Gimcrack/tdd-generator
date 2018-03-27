<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\Managers\FileManager;
use Ingenious\TddGenerator\Managers\MigrationManager;
use Ingenious\TddGenerator\Managers\RelationshipManager;
use Ingenious\TddGenerator\Managers\VueManager;
use Ingenious\TddGenerator\Utility\Converter;
use Ingenious\TddGenerator\Managers\StubManager;
use Ingenious\TddGenerator\Managers\RoutesManager;

class Generator {

    /**
     * The Stub Manager
     *
     * @var StubManager
     */
    protected $stubs;

    /**
     * The Routes Manager
     *
     * @var RoutesManager
     */
    protected $routes;

    /**
     * The Params object
     *
     * @var Params
     */
    protected $params;

    public $output = [];

    /**
     * Handles migrations
     *
     * @var MigrationManager
     */
    protected $migrations;

    /**
     * Handles vue components
     *
     * @var VueManager
     */
    protected $components;

    public function __construct( Params $params )
    {
        $this->params = $params;

        $this->stubs = StubManager::base( $this->params );

        $this->routes = RoutesManager::init(
            Converter::init( $this->params )
        );

        $this->migrations = MigrationManager::init( Converter::init( $this->params ) );

        $this->components = VueManager::init( Converter::init($this->params) );

        $this->output = collect();
    }

    public static function init(Params $params)
    {
        return new static($params);
    }

    public function setStubs(StubManager $stubs)
    {
        $this->stubs = $stubs;

        return $this;
    }

    public function setRoutes(RoutesManager $routes)
    {
        $this->routes = $routes;

        return $this;
    }

    public function setMigrations(MigrationManager $migrations)
    {
        $this->migrations = $migrations;

        return $this;
    }

    public function setComponents(VueManager $components)
    {
        $this->components = $components;

        return $this;
    }

    /**
     * Append the output
     *
     * @return $this
     */
    public function appendOutput()
    {
        foreach( func_get_args() as $output ) {
            if ( is_string($output) && strpos($output, "\n") !== false )
                $output = explode("\n",$output);

            $this->output = $this->output->merge( collect($output) );
        }

        return $this;
    }

    /**
     * Description
     * @method handle
     *
     * @param Params $params
     * @return static
     */
    public static function handle( Params $params )
    {
        $generator = new static( $params );

        return $generator
            ->reinit()
            ->processRoutes()
            ->processStubs()
            ->components()
            ->processParent()
            ->relationships();

    }

    /**
     * Process the vue components
     *
     * @return $this
     */
    public function components()
    {
        if ( ! $this->params->hasTag('component') )
            return $this;

        $form_def = $this->stubs->converter->interpolator->run("\t\t\t[thing] : require('./components/forms/[thing]'),");



        $this->appendOutput(
            "Setting up the vue components",
            $this->components->run(),

            "Registering the form definitions",
            FileManager::insert(
                FileManager::js('app'),
                $form_def,
                FileManager::lineNum(FileManager::js('app'),"form_definitions") + 1
            )
        );

        return $this;
    }

    /**
     * Process the nested relationship stubs
     *
     * @return $this
     */
    public function processNested()
    {
        if ( ! $this->params->hasTag('relationships') )
            return $this;

        if ( ! $this->params->parent->model )
            return $this;

        $this->appendOutput("Setting up the parent files");

        return $this->setStubs(StubManager::parent( $this->params ))
            ->processStubs();
    }

    /**
     * Process the child stubs
     *
     * @return $this
     */
    public function processChild()
    {
        if ( ! $this->params->hasTag('relationships') )
            return $this;

        if ( ! $this->params->parent->model )
            return $this;

        $params = clone($this->params);

        $params->setChildren( $this->params->model->model )
            ->setModel( $this->params->parent->model )
            ->setParent(null);

        return $this->setStubs(
                StubManager::base( $params )
            )
            ->processStubs()
            ->setRoutes(RoutesManager::init(
                Converter::init( $params )
            ))
            ->processRoutes()
            ->setComponents(VueManager::init(
                Converter::init($params)
            ))
            ->components();
    }

    /**
     * Process the parent stubs, if applicable
     *
     * @return $this
     */
    public function processParent()
    {
        if ( ! $this->params->hasTag('relationships') )
            return $this;

        if ( ! $this->params->parent->model )
            return $this;

        return $this->processNested()
                ->processChild();
    }

    /**
     * Handle the relationships
     *
     * @return $this
     */
    public function relationships()
    {
        if ( ! $this->params->hasTag('relationships') )
            return $this;

        RelationshipManager::init($this->params)->handle();

        return $this;
    }

    /**
     * Setup the base files
     * @method setup
     *
     * @param Params $params
     * @return static
     */
    public static function setup( Params $params )
    {
        return static::init( $params )
            ->setStubs(StubManager::setup( $params ))
            ->processStubs();
    }

    /**
     * Setup the admin files
     * @method admin
     *
     * @param Params $params
     * @return static
     */
    public static function admin( Params $params )
    {
        return static::init( $params )
            ->setStubs(StubManager::admin( $params ))
            ->processStubs();
    }

    /**
     * Setup the parent files
     * @method parent
     *
     * @param Params $params
     * @return static
     */
    public static function parent( Params $params )
    {
        return static::init( $params )
            ->setStubs(StubManager::parent( $params ))
            ->processStubs();
    }

    /**
     * Setup the frontend files
     * @method frontend
     *
     * @param Params $params
     * @return static
     */
    public static function frontend( Params $params )
    {
        return static::init( $params )
            ->setStubs(StubManager::frontend( $params ))
            ->processStubs();
    }

    /**
     * Setup the chat files
     * @method chat
     *
     * @param Params $params
     * @return static
     */
    public static function chat( Params $params )
    {
        return static::init( $params )
            ->setStubs(StubManager::chat( $params ))
            ->processStubs();
    }

    /**
     * Cleanup previous runs
     * @method init
     *
     * @return   $this
     */
    public function reinit()
    {
        if ( ! $this->params->hasTag('migration') )
            return $this;

        $this->appendOutput( $this->migrations->reinit() );

        return $this;
    }

    /**
     * Process the routes
     * @method processRoutes
     *
     * @return   $this
     */
    private function processRoutes()
    {
        if ( ! $this->params->hasTag('route') )
            return $this;

        $this->appendOutput( $this->routes->process() );

        return $this;
    }

    /**
     * Convert the stubs
     * @method processStubs
     *
     * @return   $this
     */
    public function processStubs()
    {
        $initial_state = $this->stubs->converter->interpolator->run("\t\t\t\"[url_prefix][things]\" => \\App\\[Thing]::all(),");

        $this->appendOutput(
            $this->stubs->process(),

            "Setting up the initial state",
            FileManager::insert(
                FileManager::controller("HomeController"),
                $initial_state,
                31
            )
        );

        return $this;
    }
}
