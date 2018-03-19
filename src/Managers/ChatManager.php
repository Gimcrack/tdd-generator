<?php
/**
 * Created by PhpStorm.
 * User: jeremy
 * Date: 2018-03-18
 * Time: 10:08 AM
 */

namespace Ingenious\TddGenerator\Managers;


use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Utility\Converter;

class ChatManager
{
    /**
     * The line number where the content should be inserted
     */
    const LAYOUT_LINE_NUMBER = 33;
    const CONFIG_LINE_NUMBER = 161;
    const CONTROLLER_LINE_NUMBER = 31;

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
        $this->vue = new VueManager( new Converter(
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

        $this->output[] = FileManager::insert(
            FileManager::layout("app"),
            "<chats :user=\"{{ Auth::user()->toJson() }}\"></chats>",
            static::LAYOUT_LINE_NUMBER
        );

        $this->output[] = FileManager::insert(
            FileManager::config("app"),
            "\t\tApp\\Providers\\BroadcastServiceProvider::class,",
            static::CONFIG_LINE_NUMBER
        );

        $this->output[] = FileManager::append(
            FileManager::route("api"),
            "Route::get('chat', 'ChatController@index');\n" .
            "Route::post('chat', 'ChatController@store');\n"
        );

        $this->output[] = FileManager::insert(
            FileManager::controller("HomeController"),
            "\t\t\"chats\" => \\App\\Chat::with('user')->latest()->limit(25)->get(),",
            static::CONTROLLER_LINE_NUMBER
        );

        $this->output[] = FileManager::append(
            FileManager::model("User"),
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