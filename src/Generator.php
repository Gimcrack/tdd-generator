<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\Helpers\ManagerCollection;
use Ingenious\TddGenerator\Concerns\CollectsOutput;
use Ingenious\TddGenerator\Concerns\HasStaticGenerators;


class Generator {

    use HasStaticGenerators,
        CollectsOutput;

    /**
     * The Params object
     *
     * @var Params
     */
    public $params;

    /**
     * The collection of managers
     *
     * @var ManagerCollection
     */
    public $managers;
    /**
     * Generator constructor.
     * @param Params $params
     */
    public function __construct( Params $params )
    {
        $this->params = $params;

        $this->managers = ManagerCollection::default($params);
    }


    /**
     * Process the managers
     *
     * @return $this
     */
    public function process()
    {
        return $this->appendOutput(
            ...$this->managers->get()->map->process()
        );
    }
}
