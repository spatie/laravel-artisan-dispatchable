<?php

namespace Tests;

use Spatie\ArtisanDispatchable\Console\CacheArtisanDispatchableJobsCommand;
use Spatie\ArtisanDispatchable\Console\ClearArtisanDispatchableJobsCommand;
use Spatie\Snapshots\MatchesSnapshots;

class CacheTest extends TestCase
{
    use MatchesSnapshots;

    public function setUp(): void
    {
        parent::setUp();

        config()->set(
            'artisan-dispatchable.auto_discover_dispatchable_jobs',
            [$this->getJobsDirectory('CacheTestJobs')],
        );
    }

    /** @test */
    public function it_can_cache_the_discovered_jobs()
    {
        $this->artisan(CacheArtisanDispatchableJobsCommand::class);

        $this->assertFileExists(config('artisan-dispatchable.cache_file'));

        $content = file_get_contents(config('artisan-dispatchable.cache_file'));

        $this->assertMatchesSnapshot($content);
    }

    /** @test */
    public function it_can_removed_the_cached_jobs()
    {
        $this->artisan(CacheArtisanDispatchableJobsCommand::class);

        $this->assertFileExists(config('artisan-dispatchable.cache_file'));

        $this->artisan(ClearArtisanDispatchableJobsCommand::class);

        $this->assertFileDoesNotExist(config('artisan-dispatchable.cache_file'));
    }
}
