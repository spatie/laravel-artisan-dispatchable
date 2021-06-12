<?php

namespace Tests\TestClasses\Jobs;

use Tests\TestClasses\Models\TestModel;

class BooleanTestJob extends BaseTestJob
{
    public function __construct(
        public bool $firstBoolean,
        public bool $secondBoolean,
    )
    {

    }
}
