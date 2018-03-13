<?php

namespace Ingenious\TddGenerator;

use File;

class TddFileBackup {

    /**
     * Backup the file at the specified path
     * @method backup
     *
     * @return   string
     */
    public static function backup($path)
    {
        $output = [];
        $files = File::glob( $path );

        foreach( $files as $file ) {
            $output[] = "Backing up {$file}... Done.";
            File::move($file,"{$file}.bak");
        }

        return implode("\n",$output);
    }

}
