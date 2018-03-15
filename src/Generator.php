<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\Managers\RelationshipManager;
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

    public function __construct( Params $params )
    {
        $this->params = $params;

        $this->stubs = StubManager::base( $this->params );

        $this->routes = RoutesManager::init(
            Converter::init( $this->params )
        );
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

        $generator
            ->init()
            ->routes()
            ->process();

        if ( !! $params->parent )
        {
            $generator->output[] = "Setting up the parent files";

            $generator->stubs = StubManager::parent( $params );
            $generator->process();

            $parent_params = clone($params);
            $parent_params->setChildren( $params->model->model );
            $parent_params->setModel( $params->parent->model );
            $parent_params->setParent(null);

            $generator->stubs = StubManager::base( $parent_params );
            $generator->process();

            $generator->routes = RoutesManager::init(
                Converter::init( $parent_params )
            );
            $generator->routes();
        }

        // handle the relationships
        RelationshipManager::init($params)->handle();

        return $generator;
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
        $generator = new static( $params );

        $generator->stubs = StubManager::setup( $params );

        $generator->process();

        return $generator;
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
        $generator = new static( $params );

        $generator->stubs = StubManager::admin( $params );

        $generator->process();

        return $generator;
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
        $generator = new static( $params );

        $generator->stubs = StubManager::parent( $params );

        $generator->process();

        return $generator;
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
        $generator = new static( $params );

        $generator->stubs = StubManager::frontend($params);

        $generator->process();

        return $generator;
    }

    /**
     * Initialize the environment
     * @method init
     *
     * @return   $this
     */
    public function init()
    {
        if ( ! $this->params->force && $this->stubs->migrationExists() )
            $this->output[] = "[warn]***Skipping Migration file. It already exists.***";


        if ( $this->params->force )
            $this->output[] = $this->stubs->cleanUp();

        return $this;
    }

    /**
     * Process the routes
     * @method routes
     *
     * @return   $this
     */
    private function routes()
    {
        $this->output[] = $this->routes->process();

        return $this;
    }

    /**
     * Convert the stubs
     * @method convert
     *
     * @return   $this
     */
    public function process()
    {
        $this->output[] = $this->stubs->process();

        return $this;
    }
}
