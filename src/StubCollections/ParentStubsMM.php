<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Helpers\StubCollection;
use Ingenious\TddGenerator\Stub;

class ParentStubsMM {

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return StubCollection::tag('parent', [
            Stub::controller("ParentThingController.MM"),
            Stub::migration("XXXX_XX_XX_XXXXXX_create_[parent]_[thing]_table"),
            //Stub::request("NewParentThingRequest.MM"),
            Stub::test("Feature/ParentThingTest.MM"),
        ]);
    }
}
