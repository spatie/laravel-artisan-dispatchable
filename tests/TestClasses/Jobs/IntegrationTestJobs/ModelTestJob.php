<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;
use Tests\TestClasses\Models\TestModel;

class ModelTestJob extends BaseTestJob
{
    public function __construct(public TestModel $testModel)
    {
    }
}
