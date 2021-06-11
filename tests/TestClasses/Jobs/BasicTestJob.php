<?php

namespace Tests\TestClasses\Jobs;

use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;
use Tests\TestCase;

class BasicTestJob implements ArtisanDispatchable
{
    public function handle()
    {
        TestCase::handledJob($this);
    }
}
