<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class IntegerTestJob extends BaseTestJob
{
    public function __construct(
        public int $myInteger,
    ) {
    }
}
