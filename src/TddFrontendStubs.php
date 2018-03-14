<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;

class TddFrontendStubs {

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
            TddStub::js("Api"),
            TddStub::js("app"),
            TddStub::js("bootstrap"),

            // mixins
            TddStub::mixin("item"),
            TddStub::mixin("collection"),

            // components
            TddStub::component("Item"),
            TddStub::component("Page"),
            TddStub::component("Home"),
            TddStub::component("Vinput"),
            TddStub::component("Flash"),
            TddStub::component("BatchUpdateSelected"),
            TddStub::component("HeaderSortButton"),
            TddStub::component("ResetPassword"),
            TddStub::component("Users"),
            TddStub::component("User"),

            // sass
            TddStub::sass("_variables"),
            TddStub::sass("app"),
            TddStub::sass("buttons"),

            // views
            TddStub::view("layouts/app.blade"),
            TddStub::view("partials/nav.blade"),
            TddStub::view("home.blade"),
        ]);
    }
}
