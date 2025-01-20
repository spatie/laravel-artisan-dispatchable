<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

class ArtisanJobRepository
{
    public function registerAll(): void
    {
        $this
            ->getAll()
            ->each(function (DiscoveredArtisanJob $discoveredArtisanJob) {
                $artisanJob = new ArtisanJob($discoveredArtisanJob->jobClassName);
                Artisan::command($discoveredArtisanJob->commandSignature, function () use ($artisanJob) {
                    /** @var $this ClosureCommand */
                    $artisanJob->handleCommand($this);
                })->purpose($discoveredArtisanJob->commandDescription);
            });
    }

    public function getAll(): Collection
    {
        $cachedDispatchableJobs = $this->getCachedDispatchableJobs();

        if (! is_null($cachedDispatchableJobs)) {
            return collect($cachedDispatchableJobs)
                ->map(function (array $jobProperties) {
                    return new DiscoveredArtisanJob(...$jobProperties);
                });
        }

        return $this->getUnCachedDispatchableJobs();
    }

    public function getUnCachedDispatchableJobs(): Collection
    {
        return (new DiscoverArtisanJobs())
            ->within(config('artisan-dispatchable.auto_discover_dispatchable_jobs'))
            ->useBasePath(config('artisan-dispatchable.auto_discover_base_path'))
            ->ignoringFiles(Composer::getAutoloadedFiles(base_path('composer.json')))
            ->getArtisanDispatchableJobs();
    }

    protected function getCachedDispatchableJobs(): ?array
    {
        $cachedDispatchableJobs = config('artisan-dispatchable.cache_file');

        if (! file_exists($cachedDispatchableJobs)) {
            return null;
        }

        return require $cachedDispatchableJobs;
    }
}
