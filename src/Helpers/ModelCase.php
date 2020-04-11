<?php

namespace Ingenious\TddGenerator\Helpers;

use Illuminate\Support\Str;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;

class ModelCase {

    use CanBeInitializedStatically;

    /**
     * @var string
     */
    public $model;

    /**
     * @var string
     */
    public $capped;

    /**
     * @var string
     */
    public $capped_plural;

    /**
     * @var string
     */
    public $lower;

    /**
     * @var string
     */
    public $lower_plural;

    /**
     * Create a new Case Manager from the model
     * @method __construct
     *
     * @param $model
     */
    public function __construct($model)
    {
        if ( ! $model ) {
            $this->noModel();
            return;
        }

        $this->model = $model;
        $this->capped = Str::studly($model);
        $this->capped_plural = Str::studly( Str::plural($model) );

        $this->lower = Str::lower( Str::snake($model) );
        $this->lower_plural = Str::lower( Str::snake(Str::plural($model) ) );
    }

    /**
     * @return void
     */
    public function noModel()
    {
        $this->model = '';
        $this->capped = '';
        $this->capped_plural = '';

        $this->lower = '';
        $this->lower_plural = '';
    }
}
