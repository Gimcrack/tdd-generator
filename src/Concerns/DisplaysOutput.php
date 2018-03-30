<?php

namespace Ingenious\TddGenerator\Concerns;


trait DisplaysOutput {

    /**
     * Display the output
     * @method output
     *
     * @return void
     */
    public function output()
    {
        foreach ( func_get_args() as $processor) {

            if ( ! is_object($processor) )
            {
                $this->printOutput($processor);
                continue;
            }

            foreach( $processor->output as $comment ) {
                if ( ! is_array($comment) && strpos($comment,"\n") !== false )
                    $comment = explode("\n",$comment);

                $this->printOutput($comment);
            }
        }

        $this->info("\n");
    }

    /**
     * Print the output
     *
     * @param  array|string  $comment
     */
    private function printOutput($comment)
    {
        if ( is_array($comment) )
        {
            foreach($comment as $c)
            {
                $this->printOutput($c);
            }
            return;
        }

        if ( ! trim($comment) )
            return;

        if ( strpos($comment,"[warn]") !== false ) {
            $comment = str_replace("[warn]","",$comment);
            //$comment = str_pad( trim($comment). " ", 80, "-") . " Done.";

            $this->comment( $comment );
        }
        else {
            //$comment = str_pad( trim($comment). " ", 80, "-") . " Done.";
            $this->info( $comment );
        }
    }

}
