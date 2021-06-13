# Dispatch Laravel jobs via Artisan

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-artisan-dispatchable.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-artisan-dispatchable)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-artisan-dispatchable/run-tests?label=tests)](https://github.com/spatie/laravel-artisan-dispatchable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-artisan-dispatchable/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-artisan-dispatchable/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-artisan-dispatchable.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-artisan-dispatchable)

This package can register jobs as Artisan commands.

All you need to do is let your job implement the empty `ArtisanDispatchable` interface.

```php
use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;

class MyJob implements ArtisanDispatchable
{
    public function handle()
    {
        // perform some work...
    }
}
```

This allows the job to be executed via Artisan. 

```bash
php artisan my-job
```

By default, the handle method of the job will be executed immediately.

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

```php
$laravel-artisan-dispatchable = new Spatie\ArtisanDispatchable();
echo $laravel-artisan-dispatchable->echoPhrase('Hello, Spatie!');
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
