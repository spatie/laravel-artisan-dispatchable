<?php

namespace Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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

        ray()->newScreen($this->getName());

        $this->deleteCachedJobsFile();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Tests\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
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

        Schema::create('test_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public static function handledJob(object $job)
    {
        self::$handledJob = $job;
    }

    public function getTestDirectory(): string
    {
        return Str::replaceLast('tests', '', __DIR__);
    }

    public function getJobsDirectory(string $subDirectoryName): string
    {
        return __DIR__ . "/TestClasses/Jobs/{$subDirectoryName}";
    }

    protected function deleteCachedJobsFile()
    {
        $cache = config('artisan-dispatchable.cache_file');

        if (file_exists($cache)) {
            unlink($cache);
        }

        config()->set('artisan-dispatchable.auto_discover_base_path', $this->getTestDirectory());
    }
}
