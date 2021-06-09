<?php

namespace Spatie\ArtisanDispatchable\Commands;

use Illuminate\Console\Command;

class ArtisanDispatchableCommand extends Command
{
    public $signature = 'laravel-artisan-dispatchable';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
