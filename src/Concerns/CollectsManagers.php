<?php

namespace Ingenious\TddGenerator\Concerns;

use Ingenious\TddGenerator\Managers\InitialStateManager;
use Ingenious\TddGenerator\Managers\MigrationManager;
use Ingenious\TddGenerator\Managers\RelationshipManager;
use Ingenious\TddGenerator\Managers\RoutesManager;
use Ingenious\TddGenerator\Managers\StubManager;
use Ingenious\TddGenerator\Managers\VueManager;

trait CollectsManagers
{
    /**
     * @var  RoutesManager
     */
    protected $routes;

    /**
     * @var  InitialStateManager
     */
    protected $initial_state;

    /**
     * @var  MigrationManager
     */
    protected $migrations;

    /**
     * @var VueManager
     */
    protected $vue;

    /**
     * @var  StubManager
     */
    protected $stubs;

    /**
     * @var  RelationshipManager
     */
    protected $relationships;

    public function setStubs(StubManager $stubs)
    {
        $this->stubs = $stubs;

        return $this;
    }

    public function setRoutes(RoutesManager $routes)
    {
        $this->routes = $routes;

        return $this;
    }

    public function setMigrations(MigrationManager $migrations)
    {
        $this->migrations = $migrations;

        return $this;
    }

    public function setComponents(VueManager $vue)
    {
        $this->vue = $vue;

        return $this;
    }

    public function setInitialState(InitialStateManager $initial_state)
    {
        $this->initial_state = $initial_state;

        return $this;
    }

    public function setRelationships(RelationshipManager $relationships)
    {
        $this->relationships = $relationships;

        return $this;
    }
}