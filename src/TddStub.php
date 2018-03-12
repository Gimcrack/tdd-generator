<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\TddStubConverter;
use Ingenious\TddGenerator\Concerns\HelpsMakeStubs;

class TddStub {

    use HelpsMakeStubs;

    const STUB_PATH = __DIR__ . DIRECTORY_SEPARATOR . "stubs";

    public $name;
    public $path;
    public $type;
    public $converter;

    /**
     * Create a new stub
     * @method __construct
     *
     * @return   static
     */
    public function __construct($name, $path, $type = '.php')
    {
        // Body
        $this->name = $name;
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
     * @return   string
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
        $parts = explode("/",$this->name);

        return array_pop($parts);
    }

    /**
     * Set the converter
     * @method setConverter
     * @param  TddStubConverter  $converter
     *
     * @return   $this
     */
    public function setConverter(TddStubConverter $converter)
    {
        $this->converter = $converter;
    }
}
