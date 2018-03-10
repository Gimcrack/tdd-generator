<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Str;

class TddCaseManager {

    public $capped;

    public $capped_plural;

    public $lower;

    public $lower_plural;

    /**
     * Create a new Case Manager from the model
     * @method __construct
     *
     * @return   void
     */
    public function __construct($model)
    {
        $model = $model ?? '';
        $this->capped = Str::studly($model);
        $this->capped_plural = Str::studly( Str::plural($model) );

        $this->lower = Str::lower( Str::snake($model) );
        $this->lower_plural = Str::lower( Str::snake(Str::plural($model) ) );
    }
}
