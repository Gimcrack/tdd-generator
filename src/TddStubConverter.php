<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Facades\File;

class TddStubConverter {

    public $model;

    public $parent;

    public $params;

    public $output;

    /**
     * @var  array
     */
    private $search;

    /**
     * @var array
     */
    private $replace;

    /**
     * Initialize a new StubConverter
     * @method init
     *
     * @param TddParams $params
     * @return static
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

        $replacements = collect([
            '[Things]' => $this->model->capped_plural,
            '[things]' => $this->model->lower_plural,
            '[Thing]' => $this->model->capped,
            '[thing]' => $this->model->lower,
            'Things' => $this->model->capped_plural,
            'things' => $this->model->lower_plural,
            'Thing' => $this->model->capped,
            'thing' => $this->model->lower,
            '[Parents]' => $this->parent->capped_plural,
            '[parents]' => $this->parent->lower_plural,
            '[Parent]' => $this->parent->capped,
            '[parent]' => $this->parent->lower,
            'Parents' => $this->parent->capped_plural,
            'parents' => $this->parent->lower_plural,
            'Parent' => $this->parent->capped,
            'XXXX_XX_XX_XXXXXX' => date('Y_m_d_His'),
            '[prefix]' => $this->params->prefix,
            'actingAsUser()' => ( $this->params->admin ) ? 'actingAsAdmin()' : 'actingAsUser()',
        ]);

        $this->search = $replacements->keys()->all();

        $this->replace = $replacements->values()->all();
    }

    /**
     * Description
     * @method process
     * @param  TddStub  $stub
     *
     * @return   mixed
     */
    public function process( TddStub $stub )
    {
        // get the output filename
        $destination = $this->destination($stub);

        // create the output folder if it doesn't exist
        $this->verifyDestination($destination);

        // determine if the existing file matches the new file
        if ( $this->alreadyInPlace($stub) )
            return false;

        // determine if the existing file should be backed up or skipped
        elseif ( $this->backupOrSkip($destination) )
            return false;

        // write the output
        $this->write($stub);

        // return the conversion message
        return $this->message($stub);
    }

    /**
     * Replace the placeholders in the text
     * @method parse
     *
     * @param  string  $text
     * @return string
     */
    public function interpolate($text)
    {
        return str_replace($this->search
            , $this->replace
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
     * Backup existing or skip existing
     * @method alreadyInPlace
     * @param  TddStub  $stub
     *
     * @return   bool
     */
    private function alreadyInPlace(TddStub $stub)
    {
        if ( ! file_exists( $destination = $this->destination($stub)) )
            return false; // not in place

        $original = file_get_contents($destination);
        $new = $stub->content();

        if ( $original == $new ) {
            $this->output[] = "*** {$destination} already in place. ***";
            return true;
        }

        return false;
    }

    /**
     * Write the output to the destination
     * @method write
     *
     * @param  TddStub $stub
     * @return void
     * @throws \Exception
     */
    private function write(TddStub $stub)
    {
        $destination = $this->destination($stub);

        if ( ! file_put_contents($destination, $this->interpolate( $stub->content() ) ) )
            throw new \Exception("Could not write to $destination");
    }

}
