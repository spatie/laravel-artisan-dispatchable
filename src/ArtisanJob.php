<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionParameter;
use Spatie\ArtisanDispatchable\Exceptions\ModelNotFound;
use Spatie\ArtisanDispatchable\Exceptions\RequiredOptionMissing;

class ArtisanJob
{
    public function __construct(protected string $jobClassName)
    {
    }

    public function getFullCommand(): string
    {
        return "{$this->getCommandName()} {$this->getOptionString()}";
    }

    protected function getCommandName(): string
    {
        if ($name = $this->getDefaultForProperty('artisanName')) {
            return $name;
        }

        $shortClassName = class_basename($this->jobClassName);


        $prefix = config('artisan-dispatchable.command_name_prefix');
        $command = Str::of($shortClassName)->kebab()->beforeLast('-job');

        return $prefix
            ? "{$prefix}:{$command}"
            : $command;
    }

    public function getCommandDescription(): string
    {
        return $this->getDefaultForProperty('artisanDescription') ?? "Execute job {$this->jobClassName}";
    }

    protected function getOptionString(): string
    {
        $parameters = (new ReflectionClass($this->jobClassName))
            ->getConstructor()
            ?->getParameters() ?? [];

        return collect($parameters)
            ->map(fn (ReflectionParameter $parameter) => $parameter->name)
            ->map(fn (string $argumentName) => '{--' . Str::camel($argumentName) . '=}')
            ->add('{--queued}')
            ->implode(' ');
    }

    public function handleCommand(ClosureCommand $command): void
    {
        $parameters = $this->constructorValues($command);

        $job = new $this->jobClassName(...$parameters);

        $command->option('queued')
            ? dispatch($job)
            : dispatch_sync($job);
    }

    protected function constructorValues(ClosureCommand $command): array
    {
        $parameters = (new ReflectionClass($this->jobClassName))
            ->getConstructor()
            ?->getParameters();

        if (is_null(($parameters))) {
            return [];
        }

        return collect($parameters)
            ->map(function (ReflectionParameter $parameter) use ($command) {
                $parameterName = $parameter->getName();

                $value = $command->option($parameterName);

                if (is_null($value)) {
                    throw RequiredOptionMissing::make($this->getCommandName(), $parameterName);
                }

                $parameterType = $parameter->getType()?->getName();

                if (is_a($parameterType, Model::class, true)) {
                    $model = $parameterType::find($value);

                    if (is_null($model)) {
                        throw ModelNotFound::make($this->getCommandName(), $parameterName, $value);
                    }

                    $value = $model;
                }

                return $value;
            })
            ->all();
    }

    protected function getDefaultForProperty(string $name): mixed
    {
        $reflectionClass = new ReflectionClass($this->jobClassName);

        $defaultProperties = $reflectionClass->getDefaultProperties();

        return $defaultProperties[$name] ?? null;
    }
}
