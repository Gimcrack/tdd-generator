<?php

namespace Ingenious\TddGenerator;

use File;
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
     *
     * @return   void
     */
    public function process($stub_content, $output)
    {
        $new_content = $this->interpolate($stub_content);

        if ( ! file_exists( dirname( $output ) ) )
            mkdir( dirname($output) );

        if ( ! $this->params->force && file_exists($output) ) {
            if ( $this->params->backup )
            {
                $this->output[] = "[warn] *** Backing up {$output}. It already exists. ***";
                File::move($output, $output . ".bak");
            }
            else
            {
                $this->output[] = "[warn] *** Skipping file {$output}. It already exists. ***";
                return;
            }
        }

        if ( ! file_put_contents($output, $new_content) ) {
            throw new \Exception("Could not write to $output");
        }
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
            , '[prefix]'
            , 'actingAsUser()'
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


}
