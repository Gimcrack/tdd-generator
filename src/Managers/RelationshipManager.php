<?php

namespace Ingenious\TddGenerator\Managers;

use Illuminate\Support\Collection;
use Ingenious\TddGenerator\Generator;
use Ingenious\TddGenerator\Helpers\Converter;
use Ingenious\TddGenerator\Concerns\CollectsOutput;
use Ingenious\TddGenerator\Concerns\CanBeInitializedStatically;
use Ingenious\TddGenerator\Helpers\FileSystem;

class RelationshipManager
{
    use CanBeInitializedStatically,
        CollectsOutput;

    const MIGRATION_FOREIGN_KEY = "\t\t\t\$table->unsignedInteger('[parent]_id')->nullable();";

    const HAS_MANY =
<<<EOF

    /**
     * A [Thing] has many [Children]
     *
     * @return \\Illuminate\\Database\\Eloquent\\Relations\\HasMany
     */
    public function [children]()
    {
        return \$this->hasMany([Child]::class);
    }
EOF;

    const BELONGS_TO =
<<<EOF

    /**
	 * A [Thing] belongs to a [Parent]
	 *
	 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsTo
	 */
    public function [parent]() 
    {
    	return \$this->belongsTo([Parent]::class);
    }
EOF;

    const BELONGS_TO_MANY_BASE =
<<<EOF

    /**
	 * A [Thing] has many [Parents]
	 *
	 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany
	 */
    public function [parents]() 
    {
    	return \$this->belongsToMany([Parent]::class);
    }
EOF;

    const BELONGS_TO_MANY_PARENT =
<<<EOF

    /**
	 * A [Parent] has many [Things]
	 *
	 * @return \\Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany
	 */
    public function [things]() 
    {
    	return \$this->belongsToMany([Thing]::class);
    }
EOF;


    /**
     * @var Converter
     */
    private $converter;

    /**
     * RelationshipManager constructor.
     *
     * @param Converter $converter
     */
    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Handle the model relationships
     *
     * @return  array|Collection
     * @throws \Exception
     */
    public function process()
    {
        if ( ! $this->converter->params->hasModel() )
            return [];

        if ( $this->converter->params->m2m ){
            return $this->m2m();
        }

        $this->appendOutput([
           "TODO: Finish implementing m21 RelationshipManager"
        ]);

        //if ( $this->converter->params->parent->model )
        //    $this->processParent();
        //
        //if ( $this->converter->params->children->model )
        //    $this->processChildren();

        return $this->output;
    }

    /**
     * Process a many-to-many relationship
     *
     * @return Collection|array
     */
    private function m2m()
    {
        // base model
        $model = FileSystem::model($this->converter->params->model);

        $this->appendOutput(
            "Adding the belongsToMany relationship to the base model",
            FileSystem::append(
                $model,
                $this->converter->interpolator->run(static::BELONGS_TO_MANY_BASE),
                "end"
            )
        );

        // parent model
        $parent = FileSystem::model($this->converter->params->parent);

        $this->appendOutput(
            "Adding the belongsToMany relationship to the base model",
            FileSystem::append(
                $parent,
                $this->converter->interpolator->run(static::BELONGS_TO_MANY_PARENT),
                "end"
            )
        );

        return $this->output;
    }

    private function processChildren()
    {
        $model = FileSystem::model($this->converter->params->model);

        $this->appendOutput(
            "Adding the hasMany relationship to the parent model",
            FileSystem::append(
                $model,
                $this->converter->interpolator->run(static::HAS_MANY),
                -1
            )
        );
    }

    private function processParent()
    {
        $this->appendOutput(
            "Scaffolding the nested relationship",
            StubManager::parent($this->converter->params)->process(),
            $this->setupParentBase()
        );

        $model = FileSystem::model($this->converter->params->model);
        $migration = FileSystem::migration($this->converter->params->model);

        $this->appendOutput(
            "Adding the belongsTo relationship to the child model",
            FileSystem::append(
                $model,
                $this->converter->interpolator->run(static::BELONGS_TO),
                -1
            ),
            "Adding the foreign key to the child migration",
            FileSystem::insert(
                $migration,
                $this->converter->interpolator->run(static::MIGRATION_FOREIGN_KEY),
                FileSystem::lineNum($migration, "\$table->increments('id')") +1
            )
        );
    }

    private function setupParentBase()
    {
        $params = clone($this->converter->params)
            ->setModel( $this->converter->params->parent->model )
            ->setChildren( $this->converter->params->model->model )
            ->setParent( null );

        return Generator::handle($params);
    }

}