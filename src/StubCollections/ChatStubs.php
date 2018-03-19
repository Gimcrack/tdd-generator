<?php


namespace Ingenious\TddGenerator\StubCollections;


use Ingenious\TddGenerator\Stub;
use Illuminate\Support\Collection;

class ChatStubs
{

    /**
     * Get the stubs
     * @method get
     *
     * @return   Collection
     */
    public static function get()
    {
        return collect([
            // tests
            Stub::test("Feature/ChatTest"),
            Stub::test("Unit/ChatTest"),

            // model
            Stub::model("Chat"),

            // controller
            Stub::controller("ChatController"),

            // event
            Stub::event("ChatMessageReceived"),

            // components
            Stub::component("Chat"),
            Stub::component("Chats"),

            // route
            Stub::route("channels"),

            // migration
            Stub::migration("9999_12_31_00000000_create_chats_table"),
        ]);
    }
}