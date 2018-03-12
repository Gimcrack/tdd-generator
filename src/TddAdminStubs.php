<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\TddStub;

class TddAdminStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   void
     */
    public static function get()
    {
        return collect([
            TddStub::make("Routes/api-admin", base_path("routes") ),
            TddStub::make("Routes/api-user", base_path("routes") ),
            TddStub::make("Models/User", app_path() ),
            TddStub::make("Controllers/UserController", app_path("Http/Controllers") ),
            TddStub::make("Controllers/UserPromotionController", app_path("Http/Controllers") ),
            TddStub::make("Middleware/AuthenticateAsAdmin", app_path("Http/Middleware") ),
            TddStub::make("Middleware/Kernel", app_path("Http") ),
            TddStub::make("Migrations/2014_10_12_000000_create_users_table", database_path("Migrations") ),
            TddStub::make("Tests/Unit/UserTest", base_path("tests/Unit") ),
            TddStub::make("Tests/Feature/UserTest", base_path("tests/Feature") ),
            TddStub::make("Factories/UserFactory", database_path("Factories") ),
            TddStub::make("Providers/RouteServiceProvider", app_path("Providers") ),
            TddStub::make("Requests/NewUserRequest", app_path("Http\Requests") ),
            TddStub::make("Requests/UpdateUserRequest", app_path("Http\Requests") ),
            TddStub::make("Events/UserWasCreated", app_path("Events") ),
            TddStub::make("Events/UserWasDestroyed", app_path("Events") ),
            TddStub::make("Events/UserWasUpdated", app_path("Events") ),
        ]);
    }
}
