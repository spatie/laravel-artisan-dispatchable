<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\ArtisanDispatchable\ArtisanDispatchable
 */
class ArtisanDispatchableFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-artisan-dispatchable';
    }
}
