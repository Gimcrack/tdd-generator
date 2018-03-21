<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Stub;
use Ingenious\TddGenerator\Params;
use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Utility\Converter;
use Ingenious\TddGenerator\StubCollections\ChatStubs;
use Ingenious\TddGenerator\StubCollections\AdminStubs;
use Ingenious\TddGenerator\StubCollections\BaseStubs;
use Ingenious\TddGenerator\StubCollections\ParentStubs;
use Ingenious\TddGenerator\StubCollections\SetupStubs;
use Ingenious\TddGenerator\StubCollections\FrontendStubs;

class StubManager {

    /**
     * The StubConverter
     *
     * @var Converter
     */
    public $converter;

    /**
     * The stubs collection
     *
     * @var Collection
     */
    public $stubs;

    /**
     * @var int
     */
    public $count = 0;

    public function __construct($converter = null, $stubs = null)
    {
        $this->converter = $converter ?? new Converter( new Params );

        $this->stubs = $stubs ?? collect();
    }

    /**
     * Setup Stubs manager
     * @method setup
     *
     * @param Params $params
     * @return static
     */
    public static function setup(Params $params)
    {
        return new static( new Converter($params), SetupStubs::get() );
    }

    /**
     * Base Stubs manager
     * @method base
     *
     * @param Params $params
     * @return static
     */
    public static function base(Params $params)
    {
        $converter = new Converter($params);
        $skip_migration = $converter->migrationExists();

        return new static( $converter, BaseStubs::get($skip_migration) );
    }

    /**
     * Admin Stub manager
     * @method admin
     *
     * @param Params $params
     * @return static
     */
    public static function admin(Params $params)
    {
        return new static( new Converter($params), AdminStubs::get() );
    }

    /**
     * Chat Stub manager
     * @method chat
     *
     * @param Params $params
     * @return static
     */
    public static function chat(Params $params)
    {
        return new static( new Converter($params), ChatStubs::get() );
    }

    /**
     * Parent Stub manager
     * @method parent
     *
     * @param Params $params
     * @return static
     */
    public static function parent(Params $params)
    {
        return new static( new Converter($params), ParentStubs::get() );
    }

    /**
     * Frontend Stub manager
     * @method parent
     *
     * @param Params $params
     * @return static
     */
    public static function frontend(Params $params)
    {
        return new static( new Converter($params), FrontendStubs::get() );
    }

    /**
     * Process the stubs
     * @method process
     *
     * @return   Collection
     */
    public function process()
    {
        return $this->stubs
            ->filter( function(Stub $stub)  {
                $params = $this->converter->params;

                return $params->hasTag($stub->tags);
            })
            ->values()
            ->map( function(Stub $stub) {

                $this->count++;

                return $this->converter->process( $stub );
            })
            ->union($this->converter->output);
    }
}
