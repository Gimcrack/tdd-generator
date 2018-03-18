<?php

namespace Ingenious\TddGenerator;

use Ingenious\TddGenerator\Utility\ModelCase;

class Params {

    /**
     * The model name e.g. Post
     *
     * @var  ModelCase
     */
    public $model;

    /**
     * The model name of the parent
     *
     * @var  ModelCase
     */
    public $parent;

    /**
     * The model name of the children
     *
     * @var  ModelCase
     */
    public $children;

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
     * The url prefix
     *
     * @var  string
     */
    public $urlPrefix;

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

    public function __construct()
    {
        $this->setModel("")
            ->setParent("")
            ->setChildren("");
    }

    /**
     * Set the model
     * @method setModel
     *
     * @param  string  $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = new ModelCase($model);

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
        $this->parent = new ModelCase($parent);

        return $this;
    }

    /**
     * Set the children model
     * @method setChildren
     *
     * @param  string  $children
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = new ModelCase($children);

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

        $this->urlPrefix = ( $prefix ) ? "{$prefix}/" : "";

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
