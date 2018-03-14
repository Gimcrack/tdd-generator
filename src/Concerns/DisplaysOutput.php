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

            if ( ! trim($comment) )
                continue;

            if ( strpos($comment,"[warn]") !== false ) {
                $this->comment( trim(str_replace("[warn]","",$comment)) );
            }
            else {
                $this->info( trim($comment) );
            }
        }
    }

}
