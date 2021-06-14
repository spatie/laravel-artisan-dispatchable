<?php

namespace Tests\TestClasses\Jobs\IntegrationTestJobs;

use Tests\TestClasses\Jobs\BaseTestJob;

class CustomNameTestJob extends BaseTestJob
{
    protected string $artisanName = 'custom:name';
}
