<?php

namespace Tests;

use Spatie\ArtisanDispatchable\ArtisanJob;
use Spatie\ArtisanDispatchable\ArtisanJobsRepository;
use Spatie\ArtisanDispatchable\Exceptions\ModelNotFound;
use Spatie\ArtisanDispatchable\Exceptions\RequiredOptionMissing;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Tests\TestClasses\Jobs\IntegrationTestJobs\BasicTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\BooleanTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\IntegerTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\ModelTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\StringTestJob;
use Tests\TestClasses\Models\TestModel;

class IntegrationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ray()->newScreen($this->getName());

        config()->set(
            'artisan-dispatchable.auto_discover_dispatchable_jobs',
            [$this->getJobsDirectory('IntegrationTestJobs')]
        );

        (new ArtisanJobsRepository())->registerAll();
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

    /** @test */
    public function it_can_retrieve_a_model()
    {
        $testModel = TestModel::factory()->create();

        $this
            ->artisan("model-test --testModel={$testModel->id}")
            ->assertExitCode(0);

        $this->assertInstanceOf(ModelTestJob::class, self::$handledJob);

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

        $this->assertInstanceOf(StringTestJob::class, self::$handledJob);

        $this->assertEquals('first string', self::$handledJob->myString);
        $this->assertEquals('another string', self::$handledJob->anotherString);
    }

    /** @test */
    public function it_can_handle_integer_options()
    {
        $this
            ->artisan("integer-test --myInteger=1234")
            ->assertExitCode(0);

        $this->assertInstanceOf(IntegerTestJob::class, self::$handledJob);
        $this->assertEquals(1234, self::$handledJob->myInteger);
    }

    /** @test */
    public function it_can_handle_boolean_options()
    {
        $this
            ->artisan("boolean-test --firstBoolean=1 --secondBoolean=0")
            ->assertExitCode(0);

        $this->assertInstanceOf(BooleanTestJob::class, self::$handledJob);
        $this->assertTrue(self::$handledJob->firstBoolean);
        $this->assertFalse(self::$handledJob->secondBoolean);
    }
}
