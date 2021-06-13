<?php

namespace Spatie\ArtisanDispatchable;

class DiscoveredArtisanJob
{
    public string $jobClassName;

    public string $commandSignature;

    public static function fromCachedProperties(array $cachedProperties): static
    {
        return new static($cachedProperties['jobClassName'], $cachedProperties['commandProperties']);
    }

    public function __construct(string $jobClassName, string $commandSignature)
    {
        $this->jobClassName = $jobClassName;

        $this->commandSignature = $commandSignature;
    }

    public function toArray(): array
    {
        return [
            'jobClassName' => $this->jobClassName,
            'commandSignature' => $this->commandSignature,
        ];
    }
}
