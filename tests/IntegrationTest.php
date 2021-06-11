<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Spatie\ArtisanDispatchable\ArtisanJob;
use Spatie\ArtisanDispatchable\ArtisanJobsRepository;
use Tests\TestClasses\Jobs\BasicTestJob;

class IntegrationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ray()->newScreen($this->getName());

        config()->set('artisan-dispatchable.auto_discover_dispatchable_jobs', [$this->getJobsDirectory()]);
        config()->set('artisan-dispatchable.auto_discover_base_path', $this->getTestDirectory());

        $artisanJobs = (new ArtisanJobsRepository())->getAll();

        collect($artisanJobs)->each(function (string $className) {
            (new ArtisanJob($className))->register();
        });
    }

    /** @test */
    public function it_can_call_a_job()
    {
        $this
            ->artisan('basic-test')
            ->assertExitCode(0);

        $this->assertInstanceOf(BasicTestJob::class, self::$handledJob);
    }
}
