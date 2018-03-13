<?php

namespace Ingenious\TddGenerator\Concerns;

use File;

trait DisplaysOutput {

    /**
     * Display the output
     * @method output
     *
     * @return   void
     */
    public function output($processor)
    {
        foreach( $processor->output as $comment ) {

            if ( strpos($comment,"[warn]") !== false ) {
                $this->comment( str_replace("[warn]","",$comment) );
            }
            else {
                $this->info($comment);
            }
        }
    }

}
