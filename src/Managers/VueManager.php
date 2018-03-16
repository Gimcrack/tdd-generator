<?php

namespace Ingenious\TddGenerator\Managers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Stub;
use Ingenious\TddGenerator\Utility\Converter;
use Ingenious\TddGenerator\Utility\ModelCase;
use Ingenious\TddGenerator\StubCollections\AdminStubs;
use Ingenious\TddGenerator\StubCollections\BaseStubs;
use Ingenious\TddGenerator\StubCollections\ParentStubs;
use Ingenious\TddGenerator\StubCollections\SetupStubs;
use Ingenious\TddGenerator\StubCollections\FrontendStubs;

class VueManager {

    /**
     * The line number in app.js to insert the new components
     */
    const LINE_NUMBER = 39;

    /**
     * The stub converter
     *
     * @var Converter
     */
    private $converter;

    /**
     * The components to add
     *
     * @var array
     */
    private $components;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;

        $this->components = collect([
            "Vue.component('[things]', require('./components/[Things].vue'));",
            "Vue.component('[thing]', require('./components/[Thing].vue'));",
        ])->map( function($component) {
            return $this->converter->interpolator->run($component);
        });
    }

    public static function init(Converter $converter)
    {
        return new static($converter);
    }

    /**
     * Put the vue components in place
     *
     * @return string
     */
    public function run()
    {
        $app = FileManager::js("app");

        return $this->components->map( function($component) use ($app) {
            return FileManager::insert($app,$component,static::LINE_NUMBER);
        })->implode("\n");
    }

}
