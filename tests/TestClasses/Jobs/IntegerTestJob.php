<?php

namespace Tests\TestClasses\Jobs;

use Tests\TestClasses\Models\TestModel;

class IntegerTestJob extends BaseTestJob
{
    public function __construct(
        public int $myInteger,
    )
    {

    }
}
