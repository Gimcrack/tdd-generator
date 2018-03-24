<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;
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

    /**
     * The tags to include
     *
     * @var  Collection
     */
    public $tags;

    /**
     * Match all or match any
     *
     * @var  string
     */
    public $tag_match_mode = "any";

    public function __construct()
    {
        $this->setModel("")
            ->setParent("")
            ->setChildren("")
            ->setTags(collect("all"));
    }

    /**
     * Set the tags
     * @method setTags
     *
     * @param  string $tags
     * @return $this
     */
    public function setTags($tags = "all")
    {
        // match all of the specified tags
        if ( strpos($tags,",") !== false ) {
            $this->tags = collect(explode(",",$tags));
            $this->tag_match_mode = "all";
            return $this;
        }

        // match any of the specified tags
        $this->tags = collect(explode("|",$tags));
        $this->tag_match_mode = "any";

        return $this;
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

    /**
     * Is the tag included
     *
     * @param $tag
     * @param null $mode
     * @return bool
     */
    public function hasTag($tag, $mode = null)
    {
        $tags = collect($tag);
        $mode = $mode ?? $this->tag_match_mode;

        if ( $mode === 'all' ) {
            return $tags->intersect($this->tags)->count() >= $this->tags->count();
        }

        return $this->tags->contains('all')
            || $tags->intersect($this->tags)->isNotEmpty();
    }
}
