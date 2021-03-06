<?php

namespace Ingenious\TddGenerator\Helpers;

use Ingenious\TddGenerator\Params;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;

class Interpolator
{
    use CanBeInitializedStatically;

    /**
     * @var  array
     */
    private $search;

    /**
     * @var array
     */
    private $replace;

    /**
     * Interpolator constructor.
     *
     * @param \Ingenious\TddGenerator\Params $params
     */
    public function __construct(Params $params)
    {
        $replacements = collect([
            '[Things]' => $params->model->capped_plural,
            '[things]' => $params->model->lower_plural,
            '[Thing]' => $params->model->capped,
            '[thing]' => $params->model->lower,
            'Things' => $params->model->capped_plural,
            'things' => $params->model->lower_plural,
            'Thing' => $params->model->capped,
            'thing' => $params->model->lower,
            '[Parents]' => $params->parent->capped_plural,
            '[parents]' => $params->parent->lower_plural,
            '[Parent]' => $params->parent->capped,
            '[parent]' => $params->parent->lower,
            'Parents' => $params->parent->capped_plural,
            'parents' => $params->parent->lower_plural,
            'Parent' => $params->parent->capped,
            // 'parent' => $params->parent->lower, // don't add this one. it breaks stuff
            '[Children]' => $params->children->capped_plural,
            '[children]' => $params->children->lower_plural,
            '[Child]' => $params->children->capped,
            '[child]' => $params->children->lower,
            'Children' => $params->children->capped_plural,
            // 'children' => $params->children->lower_plural, // don't use. it breaks js files
            'Child' => $params->children->capped,
            // 'child' => $params->children->lower, // don't use. it breaks js files
            'XXXX_XX_XX_XXXXXX' => date('Y_m_d_His'),
            '[prefix]' => $params->prefix,
            '[channel_prefix]' => $params->channelPrefix ?: "",
            '[url_prefix]' => $params->urlPrefix ?: "",
            'actingAsUser()' => ( $params->admin ) ? 'actingAsAdmin()' : 'actingAsUser()',
            '.MM' => ''
        ]);

        $this->search = $replacements->keys()->all();

        $this->replace = $replacements->values()->all();
    }

    /**
     * Replace the placeholders in the text
     * @method parse
     *
     * @param  string  $text
     * @return string
     */
    public function run($text)
    {
        return str_replace($this->search
            , $this->replace
            , $text
        );
    }
}