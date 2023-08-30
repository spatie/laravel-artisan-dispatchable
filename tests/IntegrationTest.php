<?php

use Illuminate\Support\Facades\Bus;
use Spatie\ArtisanDispatchable\ArtisanJobRepository;
use Spatie\ArtisanDispatchable\Exceptions\ModelNotFound;
use Spatie\ArtisanDispatchable\Exceptions\RequiredOptionMissing;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Tests\TestClasses\Jobs\IntegrationTestJobs\ArgumentWithoutTypeTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\BasicTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\BooleanTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\CustomNameTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\IntegerTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\ModelTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\OptionalParameterTestJob;
use Tests\TestClasses\Jobs\IntegrationTestJobs\StringTestJob;
use Tests\TestClasses\Models\TestModel;

beforeEach(function () {
    config()->set(
        'artisan-dispatchable.auto_discover_dispatchable_jobs',
        [$this->getJobsDirectory('IntegrationTestJobs')]
    );

    (new ArtisanJobRepository())->registerAll();
});

it('can handle a job immediately', function () {
    $this
        ->artisan('basic-test')
        ->assertExitCode(0);

    $this->assertJobHandled(BasicTestJob::class);
});

it('can put a job on the queue', function () {
    Bus::fake();

    $this
        ->artisan('basic-test --queued')
        ->assertExitCode(0);

    Bus::assertDispatched(BasicTestJob::class);
    $this->assertJobNotHandled();
});

it('will not register jobs that did not implement the marker interface')
    ->tap(fn () => $this->artisan('invalid'))
    ->throws(CommandNotFoundException::class);

it('can retrieve a model', function () {
    $testModel = TestModel::factory()->create();

    $this
        ->artisan("model-test --testModel={$testModel->id}")
        ->assertExitCode(0);

    $this->assertJobHandled(ModelTestJob::class);

    expect(self::$handledJob->testModel->id)->toEqual($testModel->id);
});

it('will throw an exception if a model cannot be found', function () {
    $this
        ->artisan("model-test --testModel=1234")
        ->assertExitCode(0);
})->throws(ModelNotFound::class);

it('will throw an exception if a required parameter is not passed')
    ->tap(fn () => $this->artisan("model-test"))
    ->throws(RequiredOptionMissing::class);

it('can have optional parameters', function () {
    $this->artisan("optional-parameter-test")
        ->assertExitCode(0);

    $this->assertJobHandled(OptionalParameterTestJob::class);
});

it('can handle string options', function () {
    $this
        ->artisan("string-test --myString='first string' --anotherString='another string'")
        ->assertExitCode(0);

    $this->assertJobHandled(StringTestJob::class);

    expect(self::$handledJob->myString)->toEqual('first string')
        ->and(self::$handledJob->anotherString)->toEqual('another string');
});

it('can handle integer options', function () {
    $this
        ->artisan("integer-test --myInteger=1234")
        ->assertExitCode(0);

    $this->assertJobHandled(IntegerTestJob::class);
    expect(self::$handledJob->myInteger)->toEqual(1234);
});

it('can handle boolean options', function () {
    $this
        ->artisan("boolean-test --firstBoolean=1 --secondBoolean=0")
        ->assertExitCode(0);

    $this->assertJobHandled(BooleanTestJob::class);

    expect(self::$handledJob->firstBoolean)->toBeTrue()
        ->and(self::$handledJob->secondBoolean)->toBeFalse();
});

it('can use handle a custom name', function () {
    $this
        ->artisan("custom:name")
        ->assertExitCode(0);

    $this->assertJobHandled(CustomNameTestJob::class);
});

it('can accept an argument without a type', function () {
    $this
        ->artisan('argument-without-type-test --argumentWithoutType=1234')
        ->assertExitCode(0);

    $this->assertJobHandled(ArgumentWithoutTypeTestJob::class);

    expect(self::$handledJob->argumentWithoutType)->toEqual(1234);
});

it('can have a custom prefix', function () {
    config()->set('artisan-dispatchable.command_name_prefix', 'job');
    config()->set(
        'artisan-dispatchable.auto_discover_dispatchable_jobs',
        [$this->getJobsDirectory('IntegrationTestJobs')]
    );
    (new ArtisanJobRepository())->registerAll();

    $this
        ->artisan('job:basic-test')
        ->assertExitCode(0);

    $this->assertJobHandled(BasicTestJob::class);
});

it('can have a custom prefix and respect a custom name', function () {
    config()->set('artisan-dispatchable.command_name_prefix', 'job');
    config()->set(
        'artisan-dispatchable.auto_discover_dispatchable_jobs',
        [$this->getJobsDirectory('IntegrationTestJobs')]
    );
    (new ArtisanJobRepository())->registerAll();

    $this
        ->artisan("custom:name")
        ->assertExitCode(0);

    $this->assertJobHandled(CustomNameTestJob::class);
});
