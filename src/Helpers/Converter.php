<?php

namespace Ingenious\TddGenerator\Helpers;

use Ingenious\TddGenerator\Stub;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Managers\FileManager;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;

class Converter {

    use CanBeInitializedStatically;

    /**
     * The params
     *
     * @var Params
     */
    public $params;

    /**
     * The output of the command
     *
     * @var array
     */
    public $output;

    /**
     * The interpolator
     *
     * @var Interpolator
     */
    public $interpolator;

    /**
     * Converter constructor.
     *
     * @param \Ingenious\TddGenerator\Params $params
     */
    public function __construct( Params $params )
    {
        $this->params = $params;

        $this->interpolator = new Interpolator($params);

        $this->output = [];
    }

    /**
     * Description
     * @method process
     * @param  Stub  $stub
     *
     * @return   mixed
     */
    public function process( Stub $stub )
    {
        // determine if the existing file matches the
        // new file or should be backed up or skipped
        if ( $this->backupOrSkip($stub) )
            return $this->skipped($stub);

        // write the output
        $this->write($stub);

        // return the conversion message
        return $this->message($stub);
    }

    /**
     * Does the model have a migration already?
     * @method migrationExists
     *
     * @return   bool
     */
    public function migrationExists()
    {
        return FileManager::hasMigration($this->params->model->lower_plural);
    }

    /**
     * Is the content unchanged?
     *
     * @param \Ingenious\TddGenerator\Stub $stub
     * @return bool
     */
    public function unchanged(Stub $stub)
    {
        return FileManager::unchanged($this->destination($stub), $this->content($stub));
    }

    /**
     * Get the path to the new file
     * @method destination
     * @param  Stub  $stub
     *
     * @return   string
     */
    public function destination(Stub $stub)
    {
        return str_replace(["\\","/"],DIRECTORY_SEPARATOR,$stub->path)
            . DIRECTORY_SEPARATOR
            . $this->interpolator->run( $stub->filename() )
            . $stub->type;
    }

    /**
     * Get the new stub content
     * @method content
     * @param  Stub  $stub
     *
     * @return   string
     */
    public function content(Stub $stub)
    {
        return $this->interpolator->run( $stub->content() );
    }

    /**
     * Get the conversion message
     * @method message
     * @param  Stub  $stub
     *
     * @return   string
     */
    public function message(Stub $stub)
    {
        return "Creating [" . $this->interpolator->run( $stub->name ) . "]";
    }

    /**
     * Get the skipped message
     * @method message
     * @param  Stub  $stub
     *
     * @return   string
     */
    public function skipped(Stub $stub)
    {
        return "[warn]Skipping [" . $this->interpolator->run( $stub->name ) . "]";
    }

    /**
     * Backup existing or skip existing
     * @method backupOrSkip
     *
     * @param \Ingenious\TddGenerator\Stub $stub
     * @return bool
     */
    private function backupOrSkip(Stub $stub)
    {
        if ( $this->unchanged($stub) )
            return true;

        $destination = $this->destination($stub);

        if ( $this->params->force || ! FileManager::exists($destination) )
            return false; // don't skip

        if ( $this->params->backup ) {
            $this->output[] = FileManager::backup($destination);
            return false; // don't skip
        }

        $this->output[] = "[warn] *** Skipping file {$destination}. It already exists. ***";
        return true; // skip
    }

    /**
     * Write the output to the destination
     * @method write
     *
     * @param  Stub $stub
     * @return void
     * @throws \Exception
     */
    private function write(Stub $stub)
    {
        $this->output[] = FileManager::write(
            $this->destination($stub),
            $this->content($stub)
        );
    }

}
