<?php

namespace Tests\TestClasses\Jobs;

use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;
use Tests\TestCase;

abstract class BaseTestJob implements ArtisanDispatchable
{
    public $artisanDescription = 'This is the description';

    public function handle()
    {
        TestCase::handledJob($this);
    }
}
