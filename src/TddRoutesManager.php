<?php

namespace Ingenious\TddGenerator;

class TddRoutesManager {

    /**
     * The Stub Converter
     */
    protected $converter;

    /**
     * The routes file
     */
    protected $routes;

    public function __construct(TddStubConverter $converter, $routes)
    {
        $this->converter = $converter;
        $this->routes = $routes;
    }

    /**
     * Description
     * @method process
     *
     * @return   void
     */
    public function process()
    {
        $routes = base_path("routes" . DIRECTORY_SEPARATOR . $this->routes);
        $contents = file_get_contents($routes);

        $new_route = "Route::apiResource(\"{$this->converter->model_lower_plural}\",\"{$this->converter->model_capped}Controller\");";

        if  ( strpos($contents, $new_route) !== false )
        {
            return "Routes already exist";
        }

        file_put_contents($routes, $contents . "\n". $new_route);

        return "New routes added";
    }
}
