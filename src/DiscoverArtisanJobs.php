<?php

namespace Spatie\ArtisanDispatchable;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\ArtisanDispatchable\Jobs\ArtisanDispatchable;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class DiscoverArtisanJobs
{
    protected array $directories = [];

    protected string $basePath = '';

    protected string $rootNamespace = '';

    protected array $ignoredFiles = [];

    public function __construct()
    {
        $this->basePath = app()->path();
    }

    public function within(array $directories): self
    {
        $this->directories = $directories;

        return $this;
    }

    public function useBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function ignoringFiles(array $ignoredFiles): self
    {
        $this->ignoredFiles = $ignoredFiles;

        return $this;
    }

    public function getArtisanDispatchableJobs(): Collection
    {
        if (empty($this->directories)) {
            ray('nothing')->red();

            return new Collection();
        }

        $files = (new Finder())->files()->in($this->directories);

        return collect($files)
            ->reject(fn (SplFileInfo $file) => in_array($file->getPathname(), $this->ignoredFiles))
            ->map(fn (SplFileInfo $file) => $this->fullQualifiedClassNameFromFile($file))
            ->filter(function (string $eventHandlerClass) {
                return is_subclass_of($eventHandlerClass, ArtisanDispatchable::class);
            })
            ->map(function (string $className) {
                $artisanJob = (new ArtisanJob($className));

                return new DiscoveredArtisanJob(
                    $className,
                    $artisanJob->getFullCommand(),
                    $artisanJob->getCommandDescription(),
                );
            })
            ->values();
    }

    protected function fullQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        return Str::of($file->getRealPath())
            ->replaceFirst($this->basePath, '')
            ->trim(DIRECTORY_SEPARATOR)
            ->ucfirst()
            ->replaceLast('.php', '')
            ->replace([DIRECTORY_SEPARATOR, 'App\\'], ['\\', app()->getNamespace()])
            ->prepend($this->rootNamespace);
    }
}
