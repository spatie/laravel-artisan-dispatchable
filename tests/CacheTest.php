<?php

namespace Tests;

use Spatie\ArtisanDispatchable\Console\CacheArtisanDispatchableJobsCommand;

class CacheTest extends TestCase
{
    /** @test */
    public function it_can_cache_the_discovered_jobs()
    {
        $this->artisan(CacheArtisanDispatchableJobsCommand::class);

        $this->assertFileExists(config('artisan-dispatchable.cache_file'));

        dd(file_get_contents(config('artisan-dispatchable.cache_file')));
    }
}
