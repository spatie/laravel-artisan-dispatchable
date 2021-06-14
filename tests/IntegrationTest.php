<?php

namespace Tests;

use Illuminate\Support\Facades\Bus;
use Spatie\ArtisanDispatchable\ArtisanJobRepository;
use Spatie\ArtisanDispatchable\Exceptions\ModelNotFound;
use Spatie\ArtisanDispatchable\Exceptions\RequiredOptionMissing;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Tests\TestClasses\Jobs\IntegrationTestJobs\BasicTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\BooleanTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\CustomNameTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\IntegerTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\ModelTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\StringTestJob;
use Tests\TestClasses\Models\TestModel;

class IntegrationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set(
            'artisan-dispatchable.auto_discover_dispatchable_jobs',
            [$this->getJobsDirectory('IntegrationTestJobs')]
        );

        (new ArtisanJobRepository())->registerAll();
    }

    /** @test */
    public function it_can_handle_a_job_immediately()
    {
        $this
            ->artisan('basic-test')
            ->assertExitCode(0);

        $this->assertJobHandled(BasicTestJob::class);
    }

    /** @test */
    public function it_can_put_a_job_on_the_queue()
    {
        Bus::fake();

        $this
            ->artisan('basic-test --queued')
            ->assertExitCode(0);

        Bus::assertDispatched(BasicTestJob::class);
        $this->assertJobNotHandled();
    }

    /** @test */
    public function it_will_not_register_jobs_that_did_not_implement_the_marker_interface()
    {
        $this->expectException(CommandNotFoundException::class);

        $this->artisan('invalid');
    }

    /** @test */
    public function it_can_retrieve_a_model()
    {
        $testModel = TestModel::factory()->create();

        $this
            ->artisan("model-test --testModel={$testModel->id}")
            ->assertExitCode(0);

        $this->assertJobHandled(ModelTestJob::class);

        $this->assertEquals($testModel->id, self::$handledJob->testModel->id);
    }

    /** @test */
    public function it_will_throw_an_exception_if_a_model_cannot_be_found()
    {
        $this->expectException(ModelNotFound::class);

        $this
            ->artisan("model-test --testModel=1234")
            ->assertExitCode(0);
    }

    /** @test */
    public function it_will_throw_an_exception_if_a_required_parameter_is_not_passed()
    {
        $this->expectException(RequiredOptionMissing::class);

        $this->artisan("model-test");
    }

    /** @test */
    public function it_can_handle_string_options()
    {
        $this
            ->artisan("string-test --myString='first string' --anotherString='another string'")
            ->assertExitCode(0);

        $this->assertJobHandled(StringTestJob::class);

        $this->assertEquals('first string', self::$handledJob->myString);
        $this->assertEquals('another string', self::$handledJob->anotherString);
    }

    /** @test */
    public function it_can_handle_integer_options()
    {
        $this
            ->artisan("integer-test --myInteger=1234")
            ->assertExitCode(0);

        $this->assertJobHandled(IntegerTestJob::class);
        $this->assertEquals(1234, self::$handledJob->myInteger);
    }

    /** @test */
    public function it_can_handle_boolean_options()
    {
        $this
            ->artisan("boolean-test --firstBoolean=1 --secondBoolean=0")
            ->assertExitCode(0);

        $this->assertJobHandled(BooleanTestJob::class);
        $this->assertTrue(self::$handledJob->firstBoolean);
        $this->assertFalse(self::$handledJob->secondBoolean);
    }

    /** @test */
    public function it_can_use_handle_a_custom_name()
    {
        $this
            ->artisan("custom:name")
            ->assertExitCode(0);

        $this->assertJobHandled(CustomNameTestJob::class);
    }
}
