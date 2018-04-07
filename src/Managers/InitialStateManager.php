<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;
use Ingenious\TddGenerator\Helpers\FileSystem;
use function vsprintf;

class InitialStateManager {

    use CanBeInitializedStatically;

    /**
     * The converter
     *
     * @var  Converter
     */
    protected $converter;

    /**
     * The path to the HomeController file
     *
     * @var string
     */
    protected $controller;

    /**
     * The stub to interpolate
     *
     * @var string
     */
    protected $stub;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;

        $this->controller = FileSystem::controller("HomeController");

        $this->stub = vsprintf("%s%s",[
            str_repeat(" ",12),
            '"[url_prefix][things]" => \App\[Thing]::all(),'
        ]);
    }

    /**
     * Setup the composer dependencies
     * @method process
     *
     * @return   array
     */
    public function process()
    {
        if ( ! $this->converter->params->hasModel() )
            return [];

        return [
            "Setting up the initial state",
            FileSystem::insert(
                $this->controller,
                $this->converter->interpolator->run($this->stub),
                $this->lineNumber()
            )
        ];
    }

    private function lineNumber()
    {
        return FileSystem::lineNum(
            $this->controller,
            '$initial_state = collect(['
        );
    }
}
