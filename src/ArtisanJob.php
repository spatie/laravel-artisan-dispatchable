<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionParameter;

class ArtisanJob
{
    public function __construct(protected string $jobClassName)
    {
    }

    public function register(): void
    {
        $artisanJob = $this;
        ray($this->getFullCommand());
        Artisan::command($this->getFullCommand(), function () use ($artisanJob) {
            /** @var $this ClosureCommand */
            $artisanJob->handleCommand($this);
        });
    }

    public function getFullCommand(): string
    {
        return "{$this->getCommandName()} {$this->getOptionString()}";
    }

    protected function getCommandName(): string
    {
        $shortClassName = class_basename($this->jobClassName);

        return Str::beforeLast(Str::kebab($shortClassName), '-job');
    }

    protected function getOptionString(): string
    {
        $parameters = (new ReflectionClass($this->jobClassName))
            ->getConstructor()
            ?->getParameters();

        if (is_null($parameters)) {
            return '';
        }

        return collect($parameters)
            ->map(fn (ReflectionParameter $parameter) => $parameter->name)
            ->map(fn (string $argumentName) => '{--' . Str::camel($argumentName) . '=}')
            ->implode(' ');
    }

    public function handleCommand(ClosureCommand $command): void
    {
        $parameters = $this->constructorValues($command);

        $job = new $this->jobClassName(...$parameters);

        $job->handle();
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

                $parameterType = $parameter->getType()->getName();

                if (is_a($parameterType, Model::class, true)) {
                    $value = $parameterType::find($value);
                }

                return $value;
            })->all();
    }
}
