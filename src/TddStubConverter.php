<?php

namespace Ingenious\TddGenerator;

use File;
use Ingenious\TddGenerator\TddStub;
use Ingenious\TddGenerator\Commands\TddGenerate;

class TddStubConverter {

    public $model;

    public $parent;

    public $params;

    public $output;

    /**
     * Initialize a new StubConverter
     * @method init
     *
     * @return   static
     */
    public static function init(TddParams $params)
    {
        return new static($params);
    }

    public function __construct( TddParams $params )
    {
        $this->params = $params;

        $this->model = optional( new TddCaseManager($this->params->model) );

        $this->parent = optional( new TddCaseManager($this->params->parent) );

        $this->output = [];
    }

    /**
     * Description
     * @method process
     * @param  TddStub  $stub
     *
     * @return   void
     */
    public function process( TddStub $stub )
    {
        // get the output filename
        $destination = $this->destination($stub);

        // create the output folder if it doesn't exist
        $this->verifyDestination($destination);

        // determine if the existing file should be backed up or skipped
        if ( $this->backupOrSkip($destination) )
            return;

        // write the output
        $this->write($stub);

        // return the conversion message
        return $this->message($stub);
    }

    /**
     * Replace the placeholders in the text
     * @method parse
     *
     * @return   void
     */
    public function interpolate($text)
    {
        $search = [
               '[Things]'
            ,  '[things]'
            ,  '[Thing]'
            ,  '[thing]'
            ,  'Things'
            ,  'things'
            ,  'Thing'
            ,  'thing'
            ,  '[Parents]'
            ,  '[parents]'
            ,  '[Parent]'
            ,  '[parent]'
            ,  'Parents'
            ,  'parents'
            ,  'Parent'
            ,  'XXXX_XX_XX_XXXXXX'
            ,  '[prefix]'
            ,  'actingAsUser()'
        ];

        $replace = [
              $this->model->capped_plural
            , $this->model->lower_plural
            , $this->model->capped
            , $this->model->lower
            , $this->model->capped_plural
            , $this->model->lower_plural
            , $this->model->capped
            , $this->model->lower
            , $this->parent->capped_plural
            , $this->parent->lower_plural
            , $this->parent->capped
            , $this->parent->lower
            , $this->parent->capped_plural
            , $this->parent->lower_plural
            , $this->parent->capped
            , date('Y_m_d_His')
            , $this->params->prefix
            , ( $this->params->admin ) ? 'actingAsAdmin()' : 'actingAsUser()'
        ];

        return str_replace($search
            , $replace
            , $text
        );
    }

    /**
     * Does the model have a migration already?
     * @method migrationExists
     *
     * @return   bool
     */
    public function migrationExists()
    {
        $migration = "*_create_{$this->model->lower_plural}_table*";

        $files = File::glob( database_path("migrations") . DIRECTORY_SEPARATOR . $migration );

        return !! count($files);
    }

    /**
     * Get the path to the new file
     * @method destination
     * @param  TddStub  $stub
     *
     * @return   string
     */
    public function destination(TddStub $stub)
    {
        return str_replace(["\\","/"],DIRECTORY_SEPARATOR,$stub->path)
            . DIRECTORY_SEPARATOR
            . $this->interpolate( $stub->filename() )
            . $stub->type;
    }

    /**
     * Get the conversion message
     * @method message
     * @param  TddStub  $stub
     *
     * @return   string
     */
    public function message(TddStub $stub)
    {
        return str_pad("Creating [" . $this->interpolate( $stub->name ) . "] ", 75, "-") . "  Done.";
    }

    /**
     * Verify the destination exists
     * @method verifyDestination
     * @param  string  $destination
     *
     * @return   void
     */
    private function verifyDestination($destination)
    {
        if ( ! file_exists( dirname( $destination ) ) )
            mkdir( dirname($destination) );
    }

    /**
     * Backup existing or skip existing
     * @method backupOrSkip
     * @param  string  $destination
     *
     * @return   bool
     */
    private function backupOrSkip($destination)
    {
        if ( $this->params->force || ! file_exists($destination) )
            return false; // don't skip

        if ( $this->params->backup ) {
            $this->output[] = "[warn] *** Backing up {$destination}. It already exists. ***";
            File::move($destination, $destination . ".bak");
            return false; // don't skip
        }

        $this->output[] = "[warn] *** Skipping file {$destination}. It already exists. ***";
        return true; // skip
    }

    /**
     * Write the output to the destination
     * @method write
     * @param  TddStub  $stub
     *
     * @return   void
     */
    private function write(TddStub $stub)
    {
        $destination = $this->destination($stub);

        if ( ! file_put_contents($destination, $stub->content() ) )
            throw new \Exception("Could not write to $destination");
    }

}
