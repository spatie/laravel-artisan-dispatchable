<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class ArgumentWithoutTypeTestJob extends BaseTestJob
{
    public function __construct(
        public $testArg
    ) {
    }
}
