<?php

namespace Ingenious\TddGenerator;

class TddRoutesManager {

    /**
     * The Stub Converter
     *
     * @var TddStubConverter
     */
    protected $converter;

    /**
     * Initialize a new RoutesManager
     * @method init
     *
     * @param TddStubConverter $converter
     * @return static
     */
    public static function init(TddStubConverter $converter)
    {
        return new static($converter);
    }

    public function __construct(TddStubConverter $converter)
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

        $new_route = "Route::apiResource(\"{$this->converter->model->lower_plural}\",\"{$this->converter->model->capped}Controller\");";

        if  ( strpos($contents, $new_route) !== false )
        {
            return "Routes already exist";
        }

        file_put_contents($routes, $contents . "\n". $new_route);

        return "New routes added";
    }
}
