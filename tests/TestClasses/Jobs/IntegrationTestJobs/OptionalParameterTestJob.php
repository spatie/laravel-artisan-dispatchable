<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class OptionalParameterTestJob extends BaseTestJob
{
    public function __construct(public int $number = 1)
    {
    }
}
