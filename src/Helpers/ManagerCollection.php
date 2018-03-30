<?php

namespace Ingenious\TddGenerator\Helpers;

use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Managers\VueManager;
use Ingenious\TddGenerator\Managers\StubManager;
use Ingenious\TddGenerator\Managers\RoutesManager;
use Ingenious\TddGenerator\Managers\MigrationManager;
use Ingenious\TddGenerator\Managers\InitialStateManager;
use Ingenious\TddGenerator\Concerns\CollectsManagers;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;

class ManagerCollection {

    use CanBeInitializedStatically,
        CollectsManagers;

    /**
     * ManagerCollection constructor.
     *
     * @param array $managers
     */
    public function __construct($managers = [])
    {
        foreach($managers as $key => $manager) {
            $this->$key = $manager;
        }
    }

    /**
     * Set up a default manager collection
     *
     * @param \Ingenious\TddGenerator\Params $params
     * @return static
     */
    public static function default(Params $params)
    {
        $converter = Converter::init($params);

        return static::init([
            "stubs"=> StubManager::base($params),
            "routes" => RoutesManager::init($converter),
            "migrations" => MigrationManager::init($converter),
            "vue" => VueManager::init($converter),
            "initial_state" => InitialStateManager::init($converter),
        ]);
    }

    /**
     * Get the collection of managers
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return collect([
            $this->stubs,
            $this->routes,
            $this->migrations,
            $this->vue,
            $this->initial_state
        ]);
    }
}