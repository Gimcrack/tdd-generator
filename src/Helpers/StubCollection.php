<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 2018-03-19
 * Time: 2:44 PM
 */

namespace Ingenious\TddGenerator\Helpers;


use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Stub;

class StubCollection
{
    /**
     * Add the specified tag(s) to the specified stub(s)
     *
     * @param  array|string  $tags
     * @param  array|Stub|Collection $stub
     * @return Collection
     */
    public static function tag($tags, $stub)
    {
        return collect($stub)
            ->filter()
            ->each
            ->tag($tags);
    }
}