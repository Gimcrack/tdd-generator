<?php

namespace Ingenious\TddGenerator;

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
            "Routes/api-admin" => base_path("routes"),
            "Routes/api-user" => base_path("routes"),
            "Models/User" => app_path(),
            "Controllers/UserController" => app_path("Http/Controllers"),
            "Controllers/UserPromotionController" => app_path("Http/Controllers"),
            "Middleware/AuthenticateAsAdmin" => app_path("Http/Middleware"),
            "Middleware/Kernel" => app_path("Http"),
            "Migrations/2014_10_12_000000_create_users_table" => database_path("Migrations"),
            "Tests/Unit/UserTest" => base_path("tests/Unit"),
            "Tests/Feature/UserTest" => base_path("tests/Feature"),
            "Factories/UserFactory" => database_path("Factories"),
            "Providers/RouteServiceProvider" => app_path("Providers"),
            "Requests/NewUserRequest" => app_path("Http\Requests"),
            "Requests/UpdateUserRequest" => app_path("Http\Requests"),
            "Events/UserWasCreated" => app_path("Events"),
            "Events/UserWasDestroyed" => app_path("Events"),
            "Events/UserWasUpdated" => app_path("Events"),
        ]);
    }
}
