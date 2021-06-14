# Dispatch Laravel jobs via Artisan

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-artisan-dispatchable.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-artisan-dispatchable)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-artisan-dispatchable/run-tests?label=tests)](https://github.com/spatie/laravel-artisan-dispatchable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-artisan-dispatchable/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-artisan-dispatchable/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-artisan-dispatchable.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-artisan-dispatchable)

This package can register jobs as Artisan commands.

All you need to do is let your job implement the empty `ArtisanDispatchable` interface.

```php
use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;

class ProcessPodcast implements ArtisanDispatchable
{
    public function handle()
    {
        // perform some work...
    }
}
```

This allows the job to be executed via Artisan. 

```bash
php artisan process-podcast
```

By default, the handle method of the job will be executed immediately.

## Why we created this package

[Laravel's scheduler](https://laravel.com/docs/master/scheduling#introduction) will perform all tasks sequentially.  When you add a scheduled task to the scheduler, the task should perform its work as fast as possible, so no other tasks will have to wait.

If you have a task that needs to run every minute and its runtime is close to a minute, you should not use a simple Artisan command, as this will result in the delay of all other minute-ly tasks.

Long-running tasks should be performed by jobs that perform their work on the queue. Laravel has [the ability to schedule queued jobs](https://laravel.com/docs/master/scheduling#scheduling-queued-jobs). This way, those tasks will not block the scheduler.

```php
$schedule->job(new ProcessPodcast)->everyFiveMinutes();
````

The downside of this approach is that you cannot run that job via Artisan anymore. You have to choose between using an artisan command + blocking the scheduler on the one hand, and job + not blocking the scheduler on the other hand.

Using our package, you don't have to make that choice anymore. When letting your job implement `Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable`, you will not block the scheduler and can still execute the logic via Artisan.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-artisan-dispatchable.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-artisan-dispatchable)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-artisan-dispatchable
```

You can publish and run the migrations with:

Optionally, uou can publish the config file with:
```bash
php artisan vendor:publish --provider="Spatie\ArtisanDispatchable\ArtisanDispatchableServiceProvider" --tag="artisan-dispatchable-config"
```

This is the contents of the published config file:

```php
return [
    /*
     * These directories will be scanned for dispatchable jobs. They
     * will be registered automatically to Artisan.
     */
    'auto_discover_dispatchable_jobs' => [
        app()->path(),
    ],

    /*
     * This directory will be used as the base path when scanning
     * for dispatchable jobs.
     */
    'auto_discover_base_path' => base_path(),

    /*
     * In production, you likely don't want the package to auto-discover dispatchable
     * jobs every time Artisan is invoked. The package can cache discovered job.
     *
     * Here you can specify where the cache should be stored.
     */
    'cache_file' => storage_path('app/artisan-dispatchable/artisan-dispatchable-jobs.php'),
];
```

## Usage

All you need to do is let your job implement the empty `ArtisanDispatchable` interface.

```php
use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;

class ProcessPodcast implements ArtisanDispatchable
{
    public function handle()
    {
        // perform some work...
    }
}
```

This allows the job to be executed via Artisan.

```bash
php artisan process-podcast
```

This job will not be queued, but will be immediately executed inside the executed artisan command. 

### Queueing jobs via Artisan

If you want to put your job on the queue instead of executing it immediately, add the `queued` option.

```bash
php artisan process-podcast --queued
```

### Passing arguments to a job

If your job has constructor arguments, you may pass those arguments via options on the artisan command.

```php
use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;

class ProcessPodcast implements ArtisanDispatchable
{
    public function __construct(
        string $myFirstArgument, 
    ) {}

    public function handle()
    {
        // perform some work...
    }
}
```

Via artisan, you can call the job like this

```bash
php artisan process-podcast --my-first-argument="My string value"
```

### Using Eloquent models as arguments

If your job argument is an eloquent model, you may pass the id of the model to the artisan command option. 

```php
use App\Models\Podcast;
use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;

class ProcessPodcast implements ArtisanDispatchable
{
    public function __construct(
        Podcast $podcast, 
    ) {}

    public function handle()
    {
        // perform some work...
    }
}
```

Here's how you can execute this job with podcast id `1234`

```bash
php artisan process-podcast --podcast="1234"
```

### Customizing the description

To add a description to the lists of artisan command, add a property `$artisanDescription` to your job.

```php
use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;

class ProcessPodcast implements ArtisanDispatchable
{
     public $artisanDescription = 'This a custom description';

    public function handle()
    {
        // perform some work...
    }
}
```

### Caching discovered jobs

This package can automatically discover jobs that implement `ArtisanDispatchable` and what their artisan command should be through looping through all classes and performing some reflection.  In a local environment this is perfect, as the performance hit is not too bad, and you don't have to do anything special besides letting your job implement `ArtisanDispatchable`.

In a production environment, you probably don't want to loop through all classes on every request. The package contains a command to cache all discovered jobs.

```bash
php artisan artisan-dispatchable:cache-artisan-dispatchable-jobs
```

You probably want to call that command during your deployment of your app. This will create cache file at the location specified in the `cache_file` key of the `artisan-dispatchable` config file.

Should you want to clear the cache, you can execute this command:

```bash
php artisan artisan-dispatchable:clear-artisan-dispatchable-jobs
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
