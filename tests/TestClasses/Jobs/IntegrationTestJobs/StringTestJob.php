<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class StringTestJob extends BaseTestJob
{
    public function __construct(
        public string $myString,
        public string $anotherString
    ) {
    }
}
