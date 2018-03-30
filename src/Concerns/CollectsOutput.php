<?php

namespace Ingenious\TddGenerator\Concerns;

use Illuminate\Support\Collection;

trait CollectsOutput
{

    /**
     * @var Collection
     */
    public $output = [];

    /**
     * Append the output
     *
     * @return $this
     */
    public function appendOutput()
    {
        if ( empty($this->output) ) {
            $this->output = collect([]);
        }

        foreach (func_get_args() as $output) {
            if (is_string($output) && strpos($output, "\n") !== false)
                $output = explode("\n", $output);

            if ( isset($output->output) )
                $output = $output->output;

            $this->output = $this->output->merge(collect($output));
        }

        return $this;
    }
}