<?php

use Spatie\ArtisanDispatchable\Console\CacheArtisanDispatchableJobsCommand;
use Spatie\ArtisanDispatchable\Console\ClearArtisanDispatchableJobsCommand;

use function Spatie\Snapshots\assertMatchesSnapshot;

beforeEach(function () {
    config()->set(
        'artisan-dispatchable.auto_discover_dispatchable_jobs',
        [$this->getJobsDirectory('CacheTestJobs')],
    );
});

it('can cache the discovered jobs', function () {
    $this->artisan(CacheArtisanDispatchableJobsCommand::class);

    $this->assertFileExists(config('artisan-dispatchable.cache_file'));

    $content = file_get_contents(config('artisan-dispatchable.cache_file'));

    assertMatchesSnapshot($content);
});

it('can remove the cached jobs', function () {
    $this->artisan(CacheArtisanDispatchableJobsCommand::class);

    $this->assertFileExists(config('artisan-dispatchable.cache_file'));

    $this->artisan(ClearArtisanDispatchableJobsCommand::class);

    $this->assertFileDoesNotExist(config('artisan-dispatchable.cache_file'));
});
