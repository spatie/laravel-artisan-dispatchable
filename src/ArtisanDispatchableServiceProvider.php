<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Facades\Artisan;
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
        (new ArtisanJobsRepository())
            ->getAll()
            ->each(function (DiscoveredArtisanJob $discoveredArtisanJob) {
                $artisanJob = new ArtisanJob($discoveredArtisanJob->jobClassName);

                Artisan::command($discoveredArtisanJob->commandSignature, function () use ($artisanJob) {
                    /** @var $this ClosureCommand */
                    $artisanJob->handleCommand($this);
                })->purpose($discoveredArtisanJob->commandDescription);
            });
    }
}
