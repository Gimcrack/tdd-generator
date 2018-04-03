<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Helpers\FileSystem;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;

class RoutesManager {

    use CanBeInitializedStatically;

    /**
     * The Stub Converter
     *
     * @var Converter
     */
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Description
     * @method process
     *
     * @return   string
     */
    public function process()
    {
        $params = $this->converter->params;

        if ( ! $params->hasTag('route') || ! $params->hasModel() )
            return "";

        $routes = FileSystem::route($params->routes);

        FileSystem::append($routes, $this->newRoutes($params));

        return "New routes added";
    }

    /**
     * Get the new routes
     *
     * @param Params $params
     * @return string
     */
    private function newRoutes(Params $params)
    {
        return vsprintf("%s%s%s%s%s%s",[
            $this->nested($params),
            'Route::apiResource("',
            $params->model->lower_plural,
            '","',
            $params->model->capped,
            'Controller");'
        ]);
    }

    /**
     * Handle the nested route, if applicable
     * @method process
     *
     * @param Params $params
     * @return string
     */
    public function nested(Params $params)
    {
        if ( ! $params->parent->model && ! $params->children->model )
            return "";

        return ( $params->parent->model ) ? vsprintf("%s%s%s%s%s%s%s%s%s",[
            'Route::apiResource("',
            $params->parent->lower_plural,
            '.',
            $params->model->lower_plural,
            '","',
            $params->parent->capped,
            $params->model->capped,
            'Controller");',
            "\n"
        ]) : vsprintf("%s%s%s%s%s%s%s%s%s",[
            'Route::apiResource("',
            $params->model->lower_plural,
            '.',
            $params->children->lower_plural,
            '","',
            $params->model->capped,
            $params->children->capped,
            'Controller");',
            "\n"
        ]);
    }
}
