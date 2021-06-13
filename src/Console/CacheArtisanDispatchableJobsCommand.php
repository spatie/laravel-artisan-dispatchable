<?php

namespace Spatie\ArtisanDispatchable\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Spatie\ArtisanDispatchable\ArtisanJobsRepository;
use Spatie\ArtisanDispatchable\DiscoverArtisanJobs;
use Spatie\ArtisanDispatchable\DiscoveredArtisanJob;

class CacheArtisanDispatchableJobsCommand extends Command
{
    protected $signature = 'artisan-dispatchable:cache-artisan-dispatchable-jobs';

    protected $description = 'Cache all auto discovered artisan dispatchable jobs';

    public function handle(Filesystem $files): void
    {
        $this->info('Caching artisan dispatchable jobs...');

        $artisanJobs = (new ArtisanJobsRepository())
            ->getUnCachedDispatchableJobs()
            ->map(fn(DiscoveredArtisanJob $discoveredArtisanJob) => $discoveredArtisanJob->toArray())
            ->toArray();

        $cachePath = config('artisan-dispatchable.cache_file');

        $cacheDirectory = pathinfo($cachePath, PATHINFO_DIRNAME);

        $files->makeDirectory($cacheDirectory, 0755, true, true);

        $files->put(
            $cachePath,
            '<?php return '.var_export($artisanJobs, true).';'
        );

        $this->info('All done!');
    }
}
