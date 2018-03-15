<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\Concerns\HelpsMakeStubs;

class Stub {

    use HelpsMakeStubs;

    const STUB_PATH = __DIR__.DIRECTORY_SEPARATOR."stubs".DIRECTORY_SEPARATOR;

    /**
     * The stub name
     *
     * @var string
     */
    public $name;

    /**
     * The path within laravel
     *
     * @var string
     */
    public $path;

    /**
     * The type (extension)
     *
     * @var string
     */
    public $type;

    /**
     * Create a new stub
     * @method __construct
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $type
     */
    public function __construct($name, $path, $type = '.php')
    {
        $this->name = str_replace(['\\','/'], DIRECTORY_SEPARATOR, $name);
        $this->path = $path;
        $this->type = $type;
    }

    /**
     * Get the stub content
     * @method content
     *
     * @return   string
     */
    public function content()
    {
        return file_get_contents( $this->fullpath() );
    }

    /**
     * Get the full path to the stub
     * @method fullpath
     *
     * @return string
     * @throws \Exception
     */
    public function fullpath()
    {
        $path = self::STUB_PATH
            . DIRECTORY_SEPARATOR
            . str_replace(["\\","/"],DIRECTORY_SEPARATOR,$this->name)
            . ".stub";

        if ( ! file_exists($path) )
            throw new \Exception("Could not find stub in path " . $path);

        return $path;
    }

    /**
     * Get the stub filename
     * @method filename
     *
     * @return   string
     */
    public function filename()
    {
        $parts = explode(DIRECTORY_SEPARATOR,$this->name);

        return array_pop($parts);
    }
}
