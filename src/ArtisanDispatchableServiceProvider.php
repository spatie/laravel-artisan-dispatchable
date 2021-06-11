<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\ArtisanDispatchable\Console\CacheArtisanDispatchableJobsCommand;
use Spatie\ArtisanDispatchable\Console\ClearArtisanDispatchableJobsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ArtisanDispatchableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-artisan-dispatchable')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommands([
                CacheArtisanDispatchableJobsCommand::class,
                ClearArtisanDispatchableJobsCommand::class,
            ]);
    }

    public function packageBooted()
    {
        $artisanJobs = (new ArtisanJobsRepository())->getAll();

        collect($artisanJobs)->each(function (string $className) {
            (new ArtisanJob($className))->register();
        });
    }
}
