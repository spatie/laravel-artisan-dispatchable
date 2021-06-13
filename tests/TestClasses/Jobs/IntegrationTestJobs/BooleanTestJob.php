<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class BooleanTestJob extends BaseTestJob
{
    public function __construct(
        public bool $firstBoolean,
        public bool $secondBoolean,
    ) {
    }
}
