<?php

namespace Tests\TestClasses\Jobs;

use Tests\TestClasses\Models\TestModel;

class StringTestJob extends BaseTestJob
{
    public function __construct(
        public string $myString,
        public string $anotherString
    )
    {

    }
}
