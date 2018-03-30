<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 2018-03-29
 * Time: 6:22 PM
 */

namespace Ingenious\TddGenerator\Concerns;


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
     * Setup the parent files
     * @method parent
     *
     * @param Params $params
     * @return Generator
     */
    public static function parent(Params $params)
    {
        return static::handle($params, __FUNCTION__);
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