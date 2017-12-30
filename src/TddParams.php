<?php

namespace Ingenious\TddGenerator;

class TddParams {

    public $model;

    public $parent;

    public $force;

    public $prefix;

    public $admin;

    public $routes;

    /**
     * Set the model
     * @method setModel
     *
     * @return   $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set the parent model
     * @method setParent
     *
     * @return   $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Force overwriting of existing files
     * @method setForce
     *
     * @return   $this
     */
    public function setForce($force)
    {
        $this->force = $force;

        return $this;
    }

    /**
     * Set the prefix
     * @method setPrefix
     *
     * @return   $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = ( $prefix ) ? "{$prefix}." : "";

        return $this;
    }

    /**
     * Set admin
     * @method setAdmin
     *
     * @return   $this
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Set routes
     * @method setRoutes
     *
     * @return   $this
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }
}
