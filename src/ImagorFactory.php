<?php

namespace Sandstorm\LaravelImagor;

final readonly class ImagorFactory
{
    public function __construct(
        private string   $baseUrl,
        private string   $signerType,
        private string   $secret,
        private int|null $signerTruncate,
    )
    {
    }

    public function new(): ImagorPathBuilder
    {
        return new ImagorPathBuilder($this->baseUrl, $this->signerType, $this->secret, $this->signerTruncate);
    }
}
