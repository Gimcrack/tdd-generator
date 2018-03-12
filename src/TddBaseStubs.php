<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\TddStub;

class TddBaseStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   void
     */
    public static function get($skip_migration = false)
    {
        $r = collect( [
            TddStub::make("Controllers/ThingController",app_path("Http\Controllers") ),
            TddStub::make("Events/ThingWasCreated",app_path("Events") ),
            TddStub::make("Events/ThingWasDestroyed",app_path("Events") ),
            TddStub::make("Events/ThingWasUpdated",app_path("Events") ),
            TddStub::make("Factories/ThingFactory",database_path("Factories") ),
            TddStub::make("Models/Thing",app_path() ),
            TddStub::make("Requests/NewThingRequest",app_path("Http\Requests") ),
            TddStub::make("Requests/UpdateThingRequest",app_path("Http\Requests") ),
            TddStub::make("Tests/Unit/ThingTest",base_path("tests\Unit") ),
            TddStub::make("Tests/Feature/ThingTest",base_path("tests\Feature" )),
        ]);

        if ( ! $skip_migration )
            $r[] = TddStub::make("Migrations/XXXX_XX_XX_XXXXXX_create_things_table",database_path("Migrations"));

        return $r;
    }
}
