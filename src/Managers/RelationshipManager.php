<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Params;

class RelationshipManager
{
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
     * Initialize a new RelationshipManager
     *
     * @param \Ingenious\TddGenerator\Params $params
     * @return static
     */
    public static function init(Params $params)
    {
        return new static($params);
    }

    /**
     * Handle the model relationships
     *
     * @throws \Exception
     */
    public function handle()
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
            return;

        foreach( [$parent, $parent_migration] as $file )
        {
            FileManager::clean($this->childPatterns(), $file);
        }
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