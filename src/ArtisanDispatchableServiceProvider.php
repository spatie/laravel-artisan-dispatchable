<?php

namespace Spatie\ArtisanDispatchable;

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
            ->hasCommands([
                CacheArtisanDispatchableJobsCommand::class,
                ClearArtisanDispatchableJobsCommand::class,
            ]);
    }

    public function packageBooted()
    {
        (new ArtisanJobRepository())->registerAll();
    }
}
