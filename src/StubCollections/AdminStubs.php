<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Stub;

class AdminStubs {

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
            Stub::route("api-admin"),
            Stub::route("api-user"),

            // models
            Stub::model("User"),

            // controllers
            Stub::controller("HomeController"),
            Stub::controller("UserController"),
            Stub::controller("UserPromotionController"),

            // middleware
            Stub::make("Middleware/Kernel", app_path("Http") ),
            Stub::middleware("AuthenticateAsAdmin"),

            // migrations
            Stub::migration("2014_10_12_000000_create_users_table"),

            // tests
            Stub::test("Unit/UserTest"),
            Stub::test("Feature/UserTest"),

            // factories
            Stub::factory("UserFactory"),

            // providers
            Stub::provider("RouteServiceProvider"),

            // requests
            Stub::request("NewUserRequest"),
            Stub::request("UpdateUserRequest"),

            // events
            Stub::event("UserWasCreated"),
            Stub::event("UserWasDestroyed"),
            Stub::event("UserWasUpdated"),
        ]);
    }
}
