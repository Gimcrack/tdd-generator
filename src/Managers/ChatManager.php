<?php

namespace Ingenious\TddGenerator\Managers;

use Ingenious\TddGenerator\Helpers\FileSystem;
use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Helpers\Converter;

class ChatManager
{
    /**
     * The line number where the content should be inserted
     */
    const LAYOUT_LINE_NUMBER = 33;
    const CONFIG_LINE_NUMBER = 161;

    /**
     * The vue Manager
     *
     * @var  VueManager
     */
    private $vue;

    /**
     * @var  array
     */
    public $output;

    public function __construct()
    {
        $this->vue = VueManager::init( Converter::init(
            (new Params())->setModel("Chat")
        ));
    }

    /**
     * Setup and run a ChatManager
     *
     * @return static
     */
    public static function setup()
    {
        $mgr = new static();

        $mgr->run();

        return $mgr;
    }

    /**
     * Run the manager
     *
     * @return void
     */
    public function run()
    {
        $this->output[] = $this->vue->run($embed = false);

        $this->output[] = FileSystem::insert(
            FileSystem::layout("app"),
            "\t\t<chats :user=\"{{ Auth::user()->toJson() }}\"></chats>",
            static::LAYOUT_LINE_NUMBER
        );

        $this->output[] = FileSystem::insert(
            FileSystem::config("app"),
            "\t\tApp\\Providers\\BroadcastServiceProvider::class,",
            static::CONFIG_LINE_NUMBER
        );

        $this->output[] = FileSystem::append(
            FileSystem::route("api"),
            "Route::get('chat', 'ChatController@index');\n" .
            "Route::post('chat', 'ChatController@store');\n"
        );

        $this->output[] = FileSystem::insert(
            FileSystem::controller("HomeController"),
            "\t\t\t\"chats\" => \\App\\Chat::with('user')->latest()->limit(25)->get(),",
            FileSystem::lineNum(
                FileSystem::controller("HomeController"),
                '$initial_state = collect(['
            ) + 1
        );

        $this->output[] = FileSystem::append(
            FileSystem::model("User"),
            "
    /**
     * A user can have many chat messages
     * @method chats
     *
     * @return   Collection<App\Chat>
     */
    public function chats()
    {
        return \$this->hasMany(Chat::class);
    }",
            1 // 1 line from the end
        );
    }
}