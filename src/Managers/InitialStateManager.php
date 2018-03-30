<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;


class InitialStateManager {

    use CanBeInitializedStatically;

    /**
     * The line number in HomeController
     *  to insert the initial state
     *
     * @var        integer
     */
    private const LINE_NUMBER = 31;

    /**
     * The converter
     *
     * @var  Converter
     */
    protected $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
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
            FileManager::insert(
                FileManager::controller("HomeController"),
                $this->converter->interpolator->run("\t\t\t\"[url_prefix][things]\" => \\App\\[Thing]::all(),"),
                static::LINE_NUMBER
            )
        ];
    }
}
