<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Str;

class TddStubConverter {

    /**
     * The model
     */
    public $model;

    public $model_capped;

    public $model_capped_plural;

    public $model_lower;

    public $model_lower_plural;

    public $force;

    public $prefix;

    public $admin;

    public function __construct($model = null, $force = false, $prefix = null, $admin = false)
    {
        if ( $model ) {
            $this->model = $model;

            $this->model_capped = Str::title($this->model);
            $this->model_capped_plural = Str::title( Str::plural($this->model) );

            $this->model_lower = Str::lower($this->model);
            $this->model_lower_plural = Str::lower( Str::plural($this->model) );

        }

        $this->prefix = ( $prefix ) ? "{$prefix}." : "";

        $this->force = $force;

        $this->admin = $admin;
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

        if ( ! $this->force && file_exists($output) )
            throw new \Exception($output . " already exists. Try using the force option --f");

        if ( ! file_put_contents($output, $new_content) ) {
            throw new Exception("Could not write to $output");
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
            , '[things]'
            , '[Thing]'
            , '[thing]'
            ,  'Things'
            ,  'things'
            ,  'Thing'
            ,  'thing'
            ,  'XXXX_XX_XX_XXXXXX'
            , '[prefix]'
            , 'actingAsUser()'
        ];

        $replace = [
              $this->model_capped_plural
            , $this->model_lower_plural
            , $this->model_capped
            , $this->model_lower
            , $this->model_capped_plural
            , $this->model_lower_plural
            , $this->model_capped
            , $this->model_lower
            , date('Y_m_d_His')
            , $this->prefix
            , ( $this->admin ) ? 'actingAsAdmin()' : 'actingAsUser()'
        ];

        return str_replace($search
            , $replace
            , $text
        );
    }




}
