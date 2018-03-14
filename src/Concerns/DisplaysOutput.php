<?php

namespace Ingenious\TddGenerator\Concerns;


trait DisplaysOutput {

    /**
     * Display the output
     * @method output
     *
     * @param $processor
     * @return void
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
