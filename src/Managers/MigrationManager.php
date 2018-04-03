<?php

namespace Ingenious\TddGenerator\Managers;


use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;
use Ingenious\TddGenerator\Helpers\FileSystem;

class MigrationManager
{
    use CanBeInitializedStatically;

    /**
     * The stub converter
     *
     * @var Converter
     */
    private $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Reinitialize
     *
     * @return string
     */
    public function process()
    {
        $params = $this->converter->params;

        if ( ! $params->hasTag('migration') )
            return "";

        if ( ! $params->hasModel() )
            return "";

        if ( ! $this->migrationExists() )
            return "";

        if ( ! $params->force && ! $params->backup )
            return "[warn]***Skipping Migration file. It already exists.***";


        elseif ( $params->backup ) {
            $this->backup();
            return "[warn]***Backing up Migration file. It already exists.***";
        }

        elseif ( $params->force )
            return "[warn]***A Migration exists for {$params->model->model}.***";

    }

    /**
     * Cleanup unneeded migrations
     */
    public function cleanup()
    {
        return FileSystem::delete( FileSystem::migration(
            $this->converter->params->model->lower_plural, $all = true
        ) );
    }

    /**
     * Backup existing migrations
     */
    public function backup()
    {
        return FileSystem::backup( FileSystem::migration(
            $this->converter->params->model->lower_plural, $all = true
        ) );
    }

    /**
     * Does the model have a migration already?
     * @method migrationExists
     *
     * @return   bool
     */
    public function migrationExists()
    {
        return $this->converter->migrationExists();
    }
}