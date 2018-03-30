<?php

namespace Ingenious\TddGenerator\Concerns;

trait CanBeInitializedStatically {

    public static function init(...$params) {
        return new static(...$params);
    }
}
