<?php

namespace Tests\TestClasses\Jobs;

use Tests\TestCase;

class InvalidJob
{
    public function handle()
    {
        TestCase::handledJob($this);
    }
}
