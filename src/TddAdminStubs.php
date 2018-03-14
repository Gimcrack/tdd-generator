<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;

class TddAdminStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return collect([
            // routes
            TddStub::route("api-admin"),
            TddStub::route("api-user"),

            // models
            TddStub::model("User"),

            // controllers
            TddStub::controller("UserController"),
            TddStub::controller("UserPromotionController"),

            // middleware
            TddStub::make("Middleware/Kernel", app_path("Http") ),
            TddStub::middleware("AuthenticateAsAdmin"),

            // migrations
            TddStub::migration("2014_10_12_000000_create_users_table"),

            // tests
            TddStub::test("Unit/UserTest"),
            TddStub::test("Feature/UserTest"),

            // factories
            TddStub::factory("UserFactory"),

            // providers
            TddStub::provider("RouteServiceProvider"),

            // requests
            TddStub::request("NewUserRequest"),
            TddStub::request("UpdateUserRequest"),

            // events
            TddStub::event("UserWasCreated"),
            TddStub::event("UserWasDestroyed"),
            TddStub::event("UserWasUpdated"),
        ]);
    }
}
