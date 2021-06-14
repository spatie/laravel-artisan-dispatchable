<?php

namespace Spatie\ArtisanDispatchable;

class DiscoveredArtisanJob
{
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
