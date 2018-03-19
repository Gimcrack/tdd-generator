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

    /**
     * The tab for the Home page
     *
     * @var string
     */
    private $tab;

    /**
     * The pane for the Home page
     *
     * @var string
     */
    private $pane;


    public function __construct(Converter $converter)
    {
        $this->converter = $converter;

        $this->components = collect([
            "Vue.component('[things]', require('./components/[Things].vue'));",
            "Vue.component('[thing]', require('./components/[Thing].vue'));",
        ])->map( function($component) {
            return $this->converter->interpolator->run($component);
        });

        $this->tab = $this->converter->interpolator->run(
            "\t\t\t\t\t<li>\n\t\t\t\t\t\t<a id=\"[things]-tab\" @click=\"nav('[things]',\$event)\">\n\t\t\t\t\t\t\t<i class=\"fa fa-fw fa-2x fa-cogs\"></i>\n\t\t\t\t\t\t</a>\n\t\t\t\t\t</li>"
        );

        $this->pane = $this->converter->interpolator->run(
            "\t\t\t\t\t<div class=\"tab-pane\" id=\"[things]-pane\">\n\t\t\t\t\t\t<div>\n\t\t\t\t\t\t\t<[things]></[things]>\n\t\t\t\t\t\t</div>\n\t\t\t\t\t</div>"
        );

    }

    public static function init(Converter $converter)
    {
        return new static($converter);
    }

    /**
     * Put the vue components in place
     *
     * @param bool $embed
     * @return string
     */
    public function run($embed = true)
    {
        $output = [];
        // register the components
        $app = FileManager::js("app");

        $output[] = $this->components->map( function($component) use ($app) {
            return FileManager::insert($app,$component,static::LINE_NUMBER);
        })->implode("\n");


        if ( $embed )
        {
            // embed them on the home page
            $home = FileManager::component("Home");

            // embed the tab
            $tab_line = FileManager::lineNum( $home, "<!-- End Tabs -->" ) - 1;
            $output[] = FileManager::insert($home, $this->tab, $tab_line);

            // embed the pane
            $pane_line = FileManager::lineNum( $home, "<!-- End Panes -->" ) - 1;
            $output[] = FileManager::insert($home, $this->pane, $pane_line);
        }

        return implode("\n",$output);
    }

}
