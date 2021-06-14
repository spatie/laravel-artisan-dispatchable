<?php

namespace Spatie\ArtisanDispatchable\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearArtisanDispatchableJobsCommand extends Command
{
    protected $signature = 'artisan-dispatchable:clear-artisan-dispatchable-jobs';

    protected $description = 'Clear all auto-discovered artisan dispatchable jobs';

    public function handle(Filesystem $files): void
    {
        $files->delete(config('artisan-dispatchable.cache_file'));

        $this->info('Cached artisan dispatchable jobs cleared!');
    }
}
