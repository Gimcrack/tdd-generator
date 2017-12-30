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
            "Controllers/ParentThingController" => app_path("Http\Controllers"),
            "Requests/NewParentThingRequest" => app_path("Http\Requests"),
            "Requests/UpdateParentThingRequest" => app_path("Http\Requests"),
            "Tests/Feature/ParentThingTest" => base_path("tests\Feature")
        ]);
    }
}
