<?php

namespace Ingenious\TddGenerator\Managers;


use Ingenious\TddGenerator\Utility\Converter;

class MigrationManager
{
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

    public static function init(Converter $converter)
    {
        return new static($converter);
    }

    /**
     * Reinitialize
     *
     * @return string
     */
    public function reinit()
    {
        $params = $this->converter->params;

        if ( ! $this->migrationExists() )
            return "";

        if ( ! $params->force && ! $params->backup )
            return "[warn]***Skipping Migration file. It already exists.***";


        elseif ( $params->backup ) {
            $this->backup();
            return "[warn]***Backing up Migration file. It already exists.***";
        }

        elseif ( $params->force )
            return $this->cleanUp();

    }

    /**
     * Cleanup unneeded migrations
     */
    public function cleanup()
    {
        return FileManager::delete( FileManager::migration(
            $this->converter->params->model->lower_plural, $all = true
        ) );
    }

    /**
     * Backup existing migrations
     */
    public function backup()
    {
        return FileManager::backup( FileManager::migration(
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