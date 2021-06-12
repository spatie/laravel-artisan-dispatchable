<?php

namespace Spatie\ArtisanDispatchable\Exceptions;

use Exception;

class ModelNotFound extends Exception
{
    public static function make(string $commandName, string $optionName, int $value)
    {
        return new static("Could not find a model for value `{$value}` passed to option `{$optionName}` of command `{$commandName}`");
    }
}
