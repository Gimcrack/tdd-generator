<?php

namespace Ingenious\TddGenerator\StubCollections;

use Illuminate\Support\Collection;
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
        return collect([
            // js
            Stub::js("Api"),
            Stub::js("app"),
            Stub::js("bootstrap"),

            // mixins
            Stub::mixin("item"),
            Stub::mixin("collection"),

            // components
            Stub::component("Item"),
            Stub::component("Page"),
            Stub::component("Home"),
            Stub::component("Vinput"),
            Stub::component("Flash"),
            Stub::component("BatchUpdateSelected"),
            Stub::component("HeaderSortButton"),
            Stub::component("ResetPassword"),
            Stub::component("Users"),
            Stub::component("User"),

            // sass
            Stub::sass("_variables"),
            Stub::sass("app"),
            Stub::sass("buttons"),

            // views
            Stub::view("layouts/app.blade"),
            Stub::view("partials/nav.blade"),
            Stub::view("home.blade"),

            // mix config
            Stub::make("webpack.mix",base_path(),".js"),
        ]);
    }
}
