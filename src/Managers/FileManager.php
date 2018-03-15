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
     * @return string
     */
    public static function migration($name)
    {
        $name = is_string($name) ? $name : optional($name)->lower_plural;

        $migration = File::glob( database_path("migrations") . DIRECTORY_SEPARATOR . "*create_{$name}*table*.php" );

        if ( ! count($migration) )
            return "";

        return $migration[0];
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

}
