<?php

namespace Ingenious\TddGenerator;

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
        $this->output[] = "Setting up the vue components";

        $this->output[] = $this->components->run();

        return $this;
    }

    /**
     * Process the nested relationship stubs
     *
     * @return $this
     */
    public function processNested()
    {
        $this->output[] = "Setting up the parent files";

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
        if ( ! $this->params->parent )
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
        $this->output[] = $this->migrations->reinit();

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
        $this->output[] = $this->routes->process();

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
        $this->output[] = $this->stubs->process();

        return $this;
    }
}
