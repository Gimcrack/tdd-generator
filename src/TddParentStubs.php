<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;

class TddParentStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return collect( [
            TddStub::controller("ParentThingController"),
            TddStub::request("NewParentThingRequest"),
            TddStub::request("UpdateParentThingRequest"),
            TddStub::test("Feature/ParentThingTest"),
        ]);
    }
}
