<?php

namespace Spatie\ArtisanDispatchable;

class DiscoveredArtisanJob
{
    public static function fromCachedProperties(array $cachedProperties): static
    {
        return new static(
            $cachedProperties['jobClassName'],
            $cachedProperties['commandSignature'],
            $cachedProperties['commandDescription'],
        );
    }

    public function __construct(
        public string $jobClassName,
        public string $commandSignature,
        public string $commandDescription
    ) {
    }

    public function toArray(): array
    {
        return [
            'jobClassName' => $this->jobClassName,
            'commandSignature' => $this->commandSignature,
            'commandDescription' => $this->commandDescription,
        ];
    }
}
