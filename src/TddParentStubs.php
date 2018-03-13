<?php

namespace Ingenious\TddGenerator;

class TddParentStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   void
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
