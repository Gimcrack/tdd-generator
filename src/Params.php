<?php

namespace Ingenious\TddGenerator;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Helpers\ModelCase;

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
     * The channel prefix
     *
     * @var  string
     */
    public $channelPrefix;

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

    /**
     * Run the test suite
     *
     * @var  bool
     */
    public $tests;

    /**
     * Install the npm dependencies
     *
     * @var  bool
     */
    public $npm;

    /**
     * Run the migrations
     *
     * @var  bool
     */
    public $migrate;

    /**
     * Compile the assets
     *
     * @var  bool
     */
    public $compile;

    public $m2m;

    public function __construct()
    {
        $this->setModel("")
            ->setParent("")
            ->setChildren("")
            ->setM2M(false)
            ->setTags();
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
        $tags = $tags ?: "all";

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
        if ( ! is_object($model) )
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
        if ( ! is_object($parent) )
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
        if ( ! is_object($children) )
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
        $prefix = str_replace(["/","\\","."],"",$prefix);

        $this->prefix = ( $prefix ) ? "{$prefix}." : "";

        $this->urlPrefix = ( $prefix ) ? "{$prefix}/" : "";
        
        $this->channelPrefix = ( $prefix ) ? "{$prefix}:" : "";

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

    public function setTests($tests)
    {
        $this->tests = $tests;

        return $this;
    }

    public function setNpm($npm)
    {
        $this->npm = $npm;

        return $this;
    }

    public function setMigrate($migrate)
    {
        $this->migrate = $migrate;

        return $this;
    }

    public function setCompile($compile)
    {
        $this->compile = $compile;

        return $this;
    }

    public function setM2M($m2m)
    {
        $this->m2m = $m2m;

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

    /**
     * Is the model set?
     * @method hasModel
     *
     * @return   bool
     */
    public function hasModel()
    {
        return !! $this->model->model;
    }

    /**
     * Load the default param values
     *
     * @param  array  $defaults
     */
    public function loadDefaults($defaults)
    {
        $this->setBackup($defaults['backup']['value'])
            ->setPrefix($defaults['prefix']['value'])
            ->setTags($defaults['tags']['value'])
            ->setAdmin($defaults['admin']['value'])
            ->setForce($defaults['force']['value'])
            ->setRoutes($defaults['routes']['value'])
            ->setNpm($defaults['npm']['value'])
            ->setMigrate($defaults['migrate']['value'])
            ->setTests($defaults['tests']['value'])
            ->setCompile($defaults['compile']['value'])
            ->setM2M(false);
    }
}
