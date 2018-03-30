<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Helpers\ManagerCollection;
use Ingenious\TddGenerator\Managers\VueManager;
use Ingenious\TddGenerator\Managers\StubManager;
use Ingenious\TddGenerator\Managers\FileManager;
use Ingenious\TddGenerator\Managers\InitialStateManager;
use Ingenious\TddGenerator\Managers\RoutesManager;
use Ingenious\TddGenerator\Managers\MigrationManager;
use Ingenious\TddGenerator\Managers\RelationshipManager;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;

class Generator {

    use CanBeInitializedStatically;

    /**
     * The Params object
     *
     * @var Params
     */
    protected $params;

    /**
     * The collection of managers
     *
     * @var ManagerCollection
     */
    protected $managers;

    public $output = [];

    public function __construct( Params $params )
    {
        $this->output = collect();

        $this->params = $params;

        $this->managers = ManagerCollection::default($params);

        //$this->stubs = StubManager::base( $this->params );
        //
        //$this->routes = RoutesManager::init( Converter::init( $this->params ) );
        //
        //$this->migrations = MigrationManager::init( Converter::init( $this->params ) );
        //
        //$this->components = VueManager::init( Converter::init($this->params) );
        //
        //$this->initial_state = InitialStateManager::init( Converter::init($this->params) );
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
        $generator = static::init( $params );

        return $generator
            ->process();
    }

    private function process()
    {
        return $this->appendOutput(
            $this->managers->get()
                ->map
                ->process()
        );
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

        return static::handle($params);
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
            ->process();
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
            ->process();
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
            ->process();
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
            ->process();
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
            ->process();
    }

    /**
     * Set the stubs
     *
     * @param $stubs
     * @return $this
     */
    private function setStubs($stubs)
    {
        $this->managers->setStubs($stubs);

        return $this;
    }
}
