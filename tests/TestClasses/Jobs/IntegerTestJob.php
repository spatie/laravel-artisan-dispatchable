<?php

namespace Tests\TestClasses\Jobs;

class IntegerTestJob extends BaseTestJob
{
    public function __construct(
        public int $myInteger,
    ) {
    }
}
