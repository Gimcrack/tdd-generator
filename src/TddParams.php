<?php

namespace Ingenious\TddGenerator;

class TddParams {

    /**
     * The model name e.g. Post
     *
     * @var  string
     */
    public $model;

    /**
     * The model name of the
     *
     * @var  string
     */
    public $parent;

    /**
     * Force overwriting of existing files
     *
     * @var  bool
     */
    public $force;

    /**
     * The route prefix
     *
     * @var  string
     */
    public $prefix;

    /**
     * Admin only routes
     *
     * @var  bool
     */
    public $admin;

    /**
     * Backup existing files?
     *
     * @var  bool
     */
    public $backup;

    /**
     * The routes file to use
     *
     * @var  string
     */
    public $routes;

    /**
     * Set the model
     * @method setModel
     *
     * @param  string  $model
     * @return $this
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
     * @param  string  $parent
     * @return $this
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
     * @param  bool  $force
     * @return $this
     */
    public function setForce($force)
    {
        $this->force = $force;

        return $this;
    }

    /**
     * Backup and Replace existing files
     * @method setBackup
     *
     * @param  bool  $backup
     * @return $this
     */
    public function setBackup($backup)
    {
        $this->backup = $backup;

        return $this;
    }

    /**
     * Set the prefix
     * @method setPrefix
     *
     * @param  string  $prefix
     * @return $this
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
     * @param  bool  $admin
     * @return $this
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
     * @param  string  $routes
     * @return $this
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;

        return $this;
    }
}
