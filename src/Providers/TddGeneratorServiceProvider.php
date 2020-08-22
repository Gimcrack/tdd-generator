<?php

namespace Ingenious\TddGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Ingenious\TddGenerator\Commands\TddParent;
use Ingenious\TddGenerator\Commands\TddSetup;
use Ingenious\TddGenerator\Commands\TddGenerate;
use Ingenious\TddGenerator\Commands\TddCleanupBackups;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use Illuminate\Database\Eloquent\Model;

class TddGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands([
            TddSetup::class,
            TddCleanupBackups::class,
            TddGenerate::class,
            TddParent::class,
        ]);

        $this->loadViewsFrom( base_path('vendor/ingenious/tdd-generator/src/resources/views'), 'tdd-generator');

        Collection::macro('assertContains', function ($value) {
            Assert::assertTrue($this->contains($value), "Failed to assert that the collection contains the expected value");

            return $this;
        });

        Collection::macro('assertEmpty', function () {
            Assert::assertCount(0, $this, "Failed to assert that the collection was empty");

            return $this;
        });

        Collection::macro('assertCount', function ($count) {
            Assert::assertCount($count, $this, "Failed to assert that the collection had the expected count");

            return $this;
        });

        Collection::macro('assertNotEmpty', function () {
            Assert::assertTrue($this->count() > 0, "Failed to assert that the collection was not empty");

            return $this;
        });

        Collection::macro('assertMinCount', function ($count) {
            Assert::assertTrue($this->count() >= $count, "Failed to assert that the collection had at least {$count} items");

            return $this;
        });

        TestResponse::macro('assertJsonMissingModel', function(Model $model) {
            //$appends = $model->getAppends()
            //$model->setAppends([]);
            return $this->assertJsonMissingExact($model->toArray());
        });

        TestResponse::macro('assertJsonModel', function (Model $model) {
            //$model->setAppends([]);
            return $this->assertJsonFragment(['id' => $model->id]);
        });

        TestResponse::macro('assertJsonModelCollection', function (Collection $models) {
            foreach ($models as $model) {
                $this->assertJsonModel($model);
            }

            return $this;
        });

        TestResponse::macro('assertJsonResource', function (Resource $resource) {
            return $this->assertJsonFragment($resource->toArray());
        });

        TestResponse::macro('assertJsonResourceCollection', function (ResourceCollection $collection) {
            return $this->assertJsonFragment($collection->toArray());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
