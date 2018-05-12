<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Helpers\StubCollection;
use Ingenious\TddGenerator\Stub;

class FrontendStubs {

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return StubCollection::tag('frontend',[
            // js
            Stub::js("Api"),
            Stub::js("app"),
            Stub::js("bootstrap"),

            // components
            Stub::component("Home"),
            Stub::component("ResetPassword"),
            Stub::component("Users"),
            Stub::component("User"),

            // forms
            Stub::form('user'),

            // sass
            Stub::sass("app"),

            // views
            //Stub::view("layouts/app.blade"),
            //Stub::view("partials/nav.blade"),
            //Stub::view("home.blade"),
            //Stub::view("auth/login.blade"),
            //Stub::view("auth/register.blade"),
            //Stub::view("auth/passwords/reset.blade"),
            //Stub::view("auth/passwords/email.blade"),

            // mix config
            Stub::make("webpack.mix",base_path(),".js"),
        ]);
    }
}
