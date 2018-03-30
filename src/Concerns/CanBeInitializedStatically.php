<?php

namespace Ingenious\TddGenerator\Concerns;

use Ingenious\TddGenerator\Generator;

trait CanBeInitializedStatically {

    public static function init(...$params) {
        return new static(...$params);
    }

}
