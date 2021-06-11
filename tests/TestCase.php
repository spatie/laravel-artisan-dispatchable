<?php

namespace Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\ArtisanDispatchable\ArtisanDispatchableServiceProvider;
use Spatie\LaravelRay\RayServiceProvider;

class TestCase extends Orchestra
{
    public static ?object $handledJob;

    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\ArtisanDispatchable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        self::$handledJob = null;
    }

    protected function getPackageProviders($app)
    {
        return [
            ArtisanDispatchableServiceProvider::class,
            RayServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_laravel-artisan-dispatchable_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }

    public static function handledJob(object $job)
    {
        self::$handledJob = $job;
    }

    public function getTestDirectory(): string
    {
        return Str::replaceLast('tests', '',  __DIR__);
    }

    public function getJobsDirectory(): string
    {
        return __DIR__ . '/TestClasses/Jobs';
    }
}
