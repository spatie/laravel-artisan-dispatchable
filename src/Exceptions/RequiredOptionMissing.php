<?php

namespace Spatie\ArtisanDispatchable\Exceptions;

use Exception;

class RequiredOptionMissing extends Exception
{
    public static function make(string $commandName, string $missingOptionName)
    {
        return new static("It is required to pass an option `--{$missingOptionName}` to command `{$commandName}`");
    }
}
