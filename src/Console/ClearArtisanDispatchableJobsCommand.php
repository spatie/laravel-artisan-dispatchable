<?php

namespace Spatie\ArtisanDispatchable\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearArtisanDispatchableJobsCommand extends Command
{
    protected $signature = 'artisan-dispatchable:clear-artisan-dispatchable-jobs';

    protected $description = 'Clear cached event handlers';

    public function handle(Filesystem $files): void
    {
        $files->delete(config('artisan-dispatchable.cache_file'));

        $this->info('Cached event handlers cleared!');
    }
}
