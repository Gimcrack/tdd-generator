<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Stub;

class ParentStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return collect( [
            Stub::controller("ParentThingController"),
            Stub::request("NewParentThingRequest"),
            Stub::request("UpdateParentThingRequest"),
            Stub::test("Feature/ParentThingTest"),
        ]);
    }
}
