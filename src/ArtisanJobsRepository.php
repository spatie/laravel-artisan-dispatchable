<?php

namespace Spatie\ArtisanDispatchable;

class ArtisanJobsRepository
{
    public function getAll(): array
    {
        $cachedDispatchableJobs = $this->getCachedDispatchableJobs();

        if (! is_null($cachedDispatchableJobs)) {
            return $cachedDispatchableJobs;
        }

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
