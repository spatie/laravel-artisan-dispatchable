<?php

namespace Tests\TestClasses\Jobs;

class StringTestJob extends BaseTestJob
{
    public function __construct(
        public string $myString,
        public string $anotherString
    ) {
    }
}
