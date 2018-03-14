<?php

namespace Ingenious\TddGenerator;

class TddGenerator {

    /**
     * The Stub Manager
     *
     * @var TddStubManager
     */
    protected $stubs;

    /**
     * The Routes Manager
     *
     * @var TddRoutesManager
     */
    protected $routes;

    /**
     * The Params object
     *
     * @var TddParams
     */
    protected $params;

    public $output = [];

    public function __construct( TddParams $params )
    {
        $this->params = $params;

        $this->stubs = TddStubManager::base( $this->params );

        $this->routes = TddRoutesManager::init(
            TddStubConverter::init( $this->params )
        );
    }

    /**
     * Description
     * @method handle
     *
     * @param TddParams $params
     * @return static
     */
    public static function handle( TddParams $params )
    {
        $generator = new static( $params );

        $generator
            ->init()
            ->routes()
            ->process();

        if ( !! $params->parent )
        {
            $generator->output[] = "Setting up the parent files";

            $generator->stubs = TddStubManager::parent( $params );

            $generator->process();
        }

        return $generator;
    }

    /**
     * Setup the base files
     * @method setup
     *
     * @param TddParams $params
     * @return static
     */
    public static function setup( TddParams $params )
    {
        $generator = new static( $params );

        $generator->stubs = TddStubManager::setup( $params );

        $generator->process();

        return $generator;
    }

    /**
     * Setup the admin files
     * @method admin
     *
     * @param TddParams $params
     * @return static
     */
    public static function admin( TddParams $params )
    {
        $generator = new static( $params );

        $generator->stubs = TddStubManager::admin( $params );

        $generator->process();

        return $generator;
    }

    /**
     * Setup the parent files
     * @method parent
     *
     * @param TddParams $params
     * @return static
     */
    public static function parent( TddParams $params )
    {
        $generator = new static( $params );

        $generator->stubs = TddStubManager::parent( $params );

        $generator->process();

        return $generator;
    }

    /**
     * Setup the frontend files
     * @method frontend
     *
     * @param TddParams $params
     * @return static
     */
    public static function frontend( TddParams $params )
    {
        $generator = new static( $params );

        $generator->stubs = TddStubManager::frontend($params);

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
