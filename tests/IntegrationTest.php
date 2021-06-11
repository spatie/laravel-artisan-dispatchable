<?php

namespace Tests;

use Spatie\ArtisanDispatchable\ArtisanJob;
use Spatie\ArtisanDispatchable\ArtisanJobsRepository;
use Symfony\Component\Console\Exception\CommandNotFoundException;
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

    /** @test */
    public function it_will_not_register_jobs_that_did_not_implement_the_marker_interface()
    {
        $this->expectException(CommandNotFoundException::class);

        $this->artisan('invalid');
    }
}
