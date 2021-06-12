<?php

namespace Tests\TestClasses\Jobs;

use Tests\TestClasses\Models\TestModel;

class ModelTestJob extends BaseTestJob
{
    public function __construct(public TestModel $testModel)
    {

    }
}
