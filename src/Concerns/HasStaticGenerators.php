<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 2018-03-29
 * Time: 6:22 PM
 */

namespace Ingenious\TddGenerator\Concerns;


use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Managers\MigrationManager;
use Ingenious\TddGenerator\Managers\RelationshipManager;
use Ingenious\TddGenerator\Managers\RoutesManager;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Managers\StubManager;

trait HasStaticGenerators
{

    use CanBeInitializedStatically;

    /**
     * Description
     * @method handle
     *
     * @param Params $params
     * @param null|string  $stubs
     * @return Generator
     */
    public static function handle(Params $params, $stubs = null)
    {
        /** @var Generator $gen */
        $gen = static::init($params);

        if ( $stubs ) {
            $gen->managers->setStubs( StubManager::$stubs($params) );
        }

        return $gen->process();
    }

    /**
     * Setup the base files
     * @method setup
     *
     * @param Params $params
     * @return Generator
     */
    public static function setup(Params $params)
    {
        return static::handle($params, __FUNCTION__);
    }

    /**
     * Setup the admin files
     * @method admin
     *
     * @param Params $params
     * @return Generator
     */
    public static function admin(Params $params)
    {
        return static::handle($params, __FUNCTION__);
    }

    /**
     * Setup the parent files for m21 relationship
     * @method parent
     *
     * @param Params $params
     * @return Generator
     */
    public static function parent(Params $params)
    {
        /** @var Generator $gen */
        $gen = static::init($params);

        $gen->managers
            ->clear()
            ->setStubs( StubManager::parent($params) )
            ->setRoutes( RoutesManager::init( Converter::init($params) ) )
            ->setRelationships( RelationshipManager::init( Converter::init($params) ));

        return $gen->process();
    }

    /**
     * Setup the parent files for m2m relationship
     * @method m2m
     *
     * @param Params $params
     * @return Generator
     */
    public static function m2m(Params $params)
    {
        /** @var Generator $gen */
        $gen = static::init($params);

        $gen->managers
            ->clear()
            ->setMigrations( MigrationManager::init( Converter::init($params) ))
            ->setStubs( StubManager::m2m($params) )
            ->setRoutes( RoutesManager::init( Converter::init($params) ) )
            ->setRelationships( RelationshipManager::init( Converter::init($params) ));

        return $gen->process();
    }

    /**
     * Setup the frontend files
     * @method frontend
     *
     * @param Params $params
     * @return Generator
     */
    public static function frontend(Params $params)
    {
        return static::handle($params, __FUNCTION__);
    }

    /**
     * Setup the chat files
     * @method chat
     *
     * @param Params $params
     * @return Generator
     */
    public static function chat(Params $params)
    {
        return static::handle($params, __FUNCTION__);
    }
}