<?php

namespace Tests\TestClasses\Jobs;

class BooleanTestJob extends BaseTestJob
{
    public function __construct(
        public bool $firstBoolean,
        public bool $secondBoolean,
    ) {
    }
}
