<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;
use Ingenious\TddGenerator\Helpers\FileSystem;

class VueManager {

    use CanBeInitializedStatically;

    /**
     * The line number in app.js to insert the new components
     */
    const LINE_NUMBER = 36;

    /**
     * The stub converter
     *
     * @var Converter
     */
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public static function init(Converter $converter)
    {
        return new static($converter);
    }

    /**
     * Process the vue parts
     *
     * @return array
     */
    public function process()
    {
        if ( ! $this->converter->params->hasTag('component') )
            return [];

        if ( ! $this->converter->params->hasModel() )
            return [];

        $form_def = $this->converter->interpolator->run("\t\t\t[thing] : require('./components/forms/[thing]'),");

        return [
            "Setting up the vue components",
            $this->run(),

            "Registering the form definitions",
            FileSystem::insert(
                FileSystem::js('app'),
                $form_def,
                FileSystem::lineNum(FileSystem::js('app'),"form_definitions") + 1
            )
        ];
    }

    /**
     * Put the vue components in place
     *
     * @param bool $embed
     * @return array
     */
    public function run($embed = true)
    {
        $components = collect([
            "Vue.component('[things]', require('./components/[Things].vue'));",
            "Vue.component('[thing]', require('./components/[Thing].vue'));",
        ])->map( function($component) {
            return $this->converter->interpolator->run($component);
        });

        // register the components
        $app = FileSystem::js("app");

        $output = $components->map( function($component) use ($app) {
            return FileSystem::insert($app,$component,static::LINE_NUMBER);
        })->all();

        return $output;
    }

}
