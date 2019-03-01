<?php

namespace Ingenious\TddGenerator\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Ingenious\TddGenerator\Utility\ModelCase;
use function is_numeric;
use function strrchr;

class FileSystem {

    /**
     * Get the asset_path
     * @method asset_path
     *
     * @return   string
     */
    public static function asset_path($path = null)
    {
        return ( app()->version() > "5.7" ) ?
            base_path("resources/$path") :
            base_path("resources/assets/$path");
    }

    /**
     * Backup the file at the specified path
     * @method backup
     *
     * @param  string|array  $path
     * @return array
     */
    public static function backup($path)
    {
        return collect(is_array($path) ? $path : File::glob($path))
            ->reject( function($file) {
                return strpos($file,".bak") !== false;
            })
            ->map( function($file) {
                File::move($file,"{$file}.bak");
                $short = str_replace(base_path(),"",$file);
                return "[warn]Backing up {$short}";
            })
            ->all();
    }

    /**
     * Make the directory for the destination if it does not exist.
     *
     * @param $destination
     */
    public static function mkdir($destination)
    {
        if ( ! file_exists( dirname( $destination ) ) )
            mkdir( dirname($destination) );
    }

    /**
     * Is the new content unchanged as old
     * @method unchanged
     *
     * @param $path
     * @param $new
     * @return bool
     */
    public static function unchanged($path, $new)
    {
        if ( ! file_exists( $path ) )
            return false; // not in place

        $original = file_get_contents($path);

        return ( $original == $new );
    }

    /**
     * Get the model file
     *
     * @param  string|ModelCase $name
     * @return string
     */
    public static function model($name)
    {
        $name = is_string($name) ? $name : optional($name)->capped;

        $model = File::glob( app_path() . DIRECTORY_SEPARATOR . $name . ".php" );

        if ( ! count($model) )
            return "";

        return $model[0];
    }

    /**
     * Get the blade layout file
     *
     * @param  string
     * @return string
     */
    public static function layout($name)
    {
        $layout = File::glob( base_path("resources/views/layouts/{$name}.blade.php") );

        if ( ! count($layout) )
            return "";

        return $layout[0];
    }

    /**
     * Get the js file
     *
     * @param  string $name
     * @return string
     */
    public static function js($name)
    {
        $js = File::glob( static::asset_path("js") . DIRECTORY_SEPARATOR . str_replace(['\\','/'], DIRECTORY_SEPARATOR, $name) . ".js" );

        if ( ! count($js) )
            return "";

        return $js[0];
    }

    /**
     * Get the controller file
     *
     * @param  string $name
     * @return string
     */
    public static function controller($name)
    {
        $controller = File::glob( app_path("Http/Controllers/{$name}.php") );

        if ( ! count($controller) )
            return "";

        return $controller[0];
    }

    /**
     * Get the route file
     *
     * @param  string  $name
     * @return string
     */
    public static function route($name)
    {
        $name = trim($name, ".php");
        $routes = File::glob( base_path("routes/{$name}.php") );

        if ( ! count($routes) )
            return "";

        return $routes[0];
    }

    /**
     * Get the component file
     *
     * @param  string  $name
     * @return string
     */
    public static function component($name)
    {
        return static::asset_path("js/components/{$name}.vue");
    }

    /**
     * Get the config file
     *
     * @param  string  $name
     * @return string
     */
    public static function config($name)
    {
        return base_path("config/{$name}.php");
    }

    /**
     * Get the contents of the file
     *
     * @param $file
     * @return bool|string
     */
    public static function get($file)
    {
        return file_get_contents($file);
    }

    /**
     *  Remove the selected patterns from the file
     *
     * @param  string|array $patterns
     * @param  string $file
     * @return array
     */
    public static function clean($patterns, $file)
    {
        $patterns = array_wrap($patterns);
        $content = static::get($file);

        foreach( $patterns as $pattern )
        {
            $content = preg_replace($pattern,"",$content);
        }

        $content = preg_replace("/\n\s*\n\s*\n/","\n\n",$content);

        return static::write($file, $content);
    }

    /**
     * Get the migration file
     *
     * @param  string|ModelCase $name
     * @param bool $all
     * @return string|array
     */
    public static function migration($name, $all = false)
    {
        $name = is_string($name) ? $name : optional($name)->lower_plural;

        $migration = File::glob( database_path("migrations/*create_{$name}*table*.php" ) );

        if ( ! count($migration) )
            return "";

        return ($all) ? $migration : $migration[0];
    }

    /**
     * Write the content to the specified path
     *
     * @param  string $path
     * @param  string $content
     * @return array
     * @throws \Exception
     */
    public static function write($path, $content)
    {
        if ( static::unchanged($path, $content) )
            return ["[warn] Skipping {$path}. Already in place"];

        static::mkdir($path);

        if ( ! file_put_contents($path, $content) )
            throw new \Exception("Could not write to {$path}");

        $short = str_replace(base_path(),"",$path);

        return ["Writing to {$short}"];
    }

    /**
     * Delete the specified file
     *
     * @param  string|array  $path
     * @return string
     */
    public static function delete($path)
    {
        $output = [];

        $files = is_array($path) ? $path : File::glob($path);

        foreach( $files as $file)
        {
            File::delete($file);
            $short = str_replace(base_path(),"",$file);
            $output[] = "[warn] Deleted {$short}";
        }

        return implode("\n",$output);
    }

    /**
     * Set the env variable
     *
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    public static function env($key, $value)
    {
        $env = base_path(".env");
        $line = static::lineNum($env, Str::upper($key) . "=");
        $new = Str::upper($key) . "=\"" . trim($value,"\"") . "\"";

        if ( $line > 0 )
            static::replace($env, $new, $line);
        else
            static::append($env, "\n" . $new);

        return ["Setting env value {$key} to {$value}"];
    }

    /**
     * Get the line number of the specified content
     *
     * @param  string  $path
     * @param  string  $content
     * @param int $after_line
     * @return int
     */
    public static function lineNum($path, $content, $after_line = 0)
    {
        $original = static::get($path);
        $index = collect( explode("\n",$original) )
            ->flip()
            ->first( function($num, $line) use ($content,$after_line) {
                return $num >= $after_line && strpos($line, $content) !== false;
            } );

        return ( $index != null ) ? $index+1 : -1;
    }

    /**
     * Replace the content in the specified file
     *
     * @param  string  $path  The path to the file
     * @param  string  $content  The new content
     * @param  string|int  $line  The content to replace
     * @return array
     */
    public static function replace($path, $content, $line)
    {
        if ( ! is_numeric($line) )
            $line = static::lineNum($path, $line);

        return static::insert($path, $content, $line, $replace = true);
    }

    /**
     * Insert the specified content into the specified file at the specified line
     *
     * @param  string  $path
     * @param  string  $content
     * @param  null|int  $line
     * @param  bool  $replace
     * @return array
     */
    public static function insert($path, $content, $line = null, $replace = false)
    {
        if ( ! $line )
            return static::append($path, $content);

        if ( static::contains($path, $content) ) {
            $short = str_replace(base_path(),"",$path);
            return ["[warn] File {$short} already contains content"];
        }

        $original = static::get($path);
        $lines = explode("\n",$original);

        $new_lines = explode("\n",$content);
        $remove = $replace ? count($new_lines) : 0;

        array_splice($lines, $line-1, $remove, $content);

        return static::write($path, implode("\n",$lines));
    }

    /**
     * Append the specified content into the specified file at the end of the file
     *
     * @param  string  $path
     * @param  string  $content
     * @param  int  $offset
     * @return array
     */
    public static function append($path, $content, $offset = 0)
    {
        if ( static::contains($path, $content) ) {
            $short = str_replace(base_path(),"",$path);
            return ["[warn] File {$short} already contains content"];
        }

        $original = trim(static::get($path));
        $lines = explode("\n",$original);


        $line = ( $offset === "end" ) ?
            count($lines) :
            count($lines) - $offset +1;

        if ( $line ) {
            return static::insert($path, $content, $line);
        }

        array_push($lines, $content);

        return static::write($path, implode("\n",$lines));
    }

    /**
     * Does a migration exist?
     * @param $migration
     * @return bool
     */
    public static function hasMigration($migration)
    {
        return static::exists(database_path("migrations") . DIRECTORY_SEPARATOR . "*create_" . $migration . "*table.php");
    }

    /**
     * Do all the given files exist?
     *
     * @param  $paths
     * @return bool
     */
    public static function exists(...$paths)
    {
        return !! collect($paths)
            ->map( function($path) {
                return !! count(File::glob( $path ));
            })
            ->min();
    }

    /**
     * Does the file contain the specified string?
     *
     * @param  string  $path
     * @param  string  $string
     * @return bool
     */
    public static function contains($path, $string)
    {
        $contents = static::get($path);
        return strpos($contents, $string) !== false;
    }

}
