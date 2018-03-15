<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Utility\Converter;

class RoutesManager {

    /**
     * The Stub Converter
     *
     * @var Converter
     */
    protected $converter;

    /**
     * Initialize a new RoutesManager
     * @method init
     *
     * @param Converter $converter
     * @return static
     */
    public static function init(Converter $converter)
    {
        return new static($converter);
    }

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
        $routes = base_path("routes" . DIRECTORY_SEPARATOR . $this->converter->params->routes);
        $contents = file_get_contents($routes);

        $new_route = $this->nested() . "Route::apiResource(\"{$this->converter->params->model->lower_plural}\",\"{$this->converter->params->model->capped}Controller\");";

        if  ( strpos($contents, $new_route) !== false )
        {
            return "Routes already exist";
        }

        file_put_contents($routes, $contents . "\n". $new_route);

        return "New routes added";
    }

    /**
     * Handle the nested route, if applicable
     * @method process
     *
     * @return   string
     */
    public function nested()
    {
        $params = $this->converter->params;

        if ( ! $params->parent )
            return "";

        return "Route::apiResource(\"{$params->parent->lower_plural}.{$params->model->lower_plural}\",\"{$params->parent->capped}{$params->model->capped}Controller\");\n";

    }
}
