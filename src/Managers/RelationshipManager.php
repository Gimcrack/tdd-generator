<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;

class RelationshipManager
{
    use CanBeInitializedStatically;

    /**
     * @var \Ingenious\TddGenerator\Params
     */
    private $params;

    /**
     * RelationshipManager constructor.
     *
     * @param \Ingenious\TddGenerator\Params $params
     */
    public function __construct(Params $params)
    {
        $this->params = $params;
    }

    /**
     * Handle the model relationships
     *
     * @throws \Exception
     */
    public function process()
    {
        if ( ! $this->params->hasTag('relationships') )
            return;

        return $this->cleanup()

    }

    /**
     * Cleanup any old migrations
     *
     * @return $this
     * @throws \Exception
     */
    private function cleanup()
    {
        $model = FileManager::model($this->params->model);
        $parent = FileManager::model($this->params->parent);
        $migration = FileManager::migration($this->params->model);
        $parent_migration = FileManager::migration($this->params->parent);

        if ( ! $model )
            throw new \Exception("Could not locate model for {$this->params->model}");

        if ( ! $migration )
            throw new \Exception("Could not locate migration for {$this->params->model}");

        foreach( [$model,$migration] as $file )
        {
            FileManager::clean($this->parentPatterns(), $file);
        }

        if ( ! $parent )
            return $this;

        foreach( [$parent, $parent_migration] as $file )
        {
            FileManager::clean($this->childPatterns(), $file);
        }

        return $this;
    }

    /**
     * Process the nested relationship stubs
     *
     * @return $this
     */
    private function processNested()
    {
        if ( ! $this->params->parent->model )
            return $this;

        $this->output[] = "Setting up the parent files";

        $this->output[] = Generator::init((StubManager::parent( $this->params ))
                    ->processStubs();

        return $this;
    }

    /**
     * Get the parent relationship patterns for cleanup
     *
     * @return array
     */
    private function parentPatterns()
    {
        return ( ! $this->params->parent->model ) ? [
            "/\/\/ -- PARENT --[\s\S]*\/\/ -- END PARENT --/",
            "/\/\/ -- CHILDREN --[\s\S]*\/\/ -- END CHILDREN --/",
        ] : [
            "/\/\/ -- PARENT --/",
            "/\/\/ -- END PARENT --/",
            "/\/\/ -- CHILDREN --[\s\S]*\/\/ -- END CHILDREN --/"
        ];
    }

    /**
     * Get the child relationship patterns for cleanup
     *
     * @return array
     */
    private function childPatterns()
    {
        return [
            "/\/\/ -- CHILDREN --/",
            "/\/\/ -- END CHILDREN --/",
            "/\/\/ -- PARENT --[\s\S]*\/\/ -- END PARENT --/",
        ];
    }

}