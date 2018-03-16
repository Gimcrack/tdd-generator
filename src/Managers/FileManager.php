<?php

namespace Ingenious\TddGenerator\Managers;

use function file_get_contents;
use Illuminate\Support\Facades\File;
use Ingenious\TddGenerator\Utility\ModelCase;
use function preg_replace;

class FileManager {

    /**
     * Backup the file at the specified path
     * @method backup
     *
     * @param  string  $path
     * @return string
     */
    public static function backup($path)
    {
        $output = [];
        $files = File::glob( $path );

        foreach( $files as $file ) {
            $output[] = "[warn] *** Backing up {$file}. It already exists. ***";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
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
     * Get the js file
     *
     * @param  string $name
     * @return string
     */
    public static function js($name)
    {
        $js = File::glob( base_path("resources/assets/js") . DIRECTORY_SEPARATOR . str_replace(['\\','/'], DIRECTORY_SEPARATOR, $name) . ".js" );

        if ( ! count($js) )
            return "";

        return $js[0];
    }

    /**
     * Get the route file
     *
     * @param  string  $name
     * @return string
     */
    public static function route($name)
    {
        return base_path("routes" . DIRECTORY_SEPARATOR . $name);
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
     * @return string
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

        $migration = File::glob( database_path("migrations") . DIRECTORY_SEPARATOR . "*create_{$name}*table*.php" );

        if ( ! count($migration) )
            return "";

        return ($all) ? $migration : $migration[0];
    }

    /**
     * Write the content to the specified path
     *
     * @param  string $path
     * @param  string $content
     * @return string
     * @throws \Exception
     */
    public static function write($path, $content)
    {
        if ( static::unchanged($path, $content) )
            return "[warn] Skipping {$path}. Already in place";

        static::mkdir($path);

        if ( ! file_put_contents($path, $content) )
            throw new \Exception("Could not write to {$path}");

        return "Writing to {$path}. . . Done.";
    }

    /**
     * Delete the specified file
     *
     * @param  string  $path
     * @return string
     */
    public static function delete($path)
    {
        $output = [];
        foreach( File::glob($path) as $file)
        {
            File::delete($file);
            $output[] = "[warn] Deleted {$path}";
        }

        return implode("\n",$output);
    }

    /**
     * Insert the specified content into the specified file at the specified line
     *
     * @param  string  $path
     * @param  string  $content
     * @param null|int  $line
     * @return string
     */
    public static function insert($path, $content, $line = null)
    {
        if ( ! $line )
            return static::append($path, $content);

        if ( static::contains($path, $content) )
            return "[warn] File {$path} already contains {$content}";

        $original = static::get($path);
        $lines = explode("\n",$original);
        array_splice($lines, $line-1, 0, $content);

        return static::write($path, implode("\n",$lines));
    }

    /**
     * Append the specified content into the specified file at the end of the file
     *
     * @param  string  $path
     * @param  string  $content
     * @return string
     */
    public static function append($path, $content)
    {
        if ( static::contains($path, $content) )
            return "[warn] File {$path} already contains {$content}";

        $original = static::get($path);
        $lines = explode("\n",$original);
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
     * Does the file exists
     *
     * @param $path
     * @return bool
     */
    public static function exists($path)
    {
        return !! count(File::glob( $path ));
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
