<?php

namespace Spatie\ArtisanDispatchable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\ArtisanDispatchable\Commands\ArtisanDispatchableCommand;

class ArtisanDispatchableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-artisan-dispatchable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-artisan-dispatchable_table')
            ->hasCommand(ArtisanDispatchableCommand::class);
    }
}
