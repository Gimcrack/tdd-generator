<?php

namespace Ingenious\TddGenerator;

class TddBaseStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   void
     */
    public static function get()
    {
        return collect( [
            "Controllers/ThingController" => app_path("Http\Controllers"),
            "Events/ThingWasCreated" => app_path("Events"),
            "Events/ThingWasDestroyed" => app_path("Events"),
            "Events/ThingWasUpdated" => app_path("Events"),
            "Factories/ThingFactory" => database_path("Factories"),
            "Migrations/XXXX_XX_XX_XXXXXX_create_things_table" => database_path("Migrations"),
            "Models/Thing" => app_path(),
            "Requests/NewThingRequest" => app_path("Http\Requests"),
            "Requests/UpdateThingRequest" => app_path("Http\Requests"),
            "Tests/Unit/ThingTest" => base_path("tests\Unit"),
            "Tests/Feature/ThingTest" => base_path("tests\Feature")
        ]);
    }
}
