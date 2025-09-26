<?php

namespace Sandstorm\LaravelImagor;

final readonly class ImagorFactory
{
    public function __construct(
        private string   $baseUrl,
        private string   $signerType,
        private string   $secret,
        private int|null $signerTruncate,
        /**
         * @var array the key is the original (Laravel) path prefix, the value is the corresponding Imagor path prefix
         */
        private array    $pathMap = [],
    )
    {
    }

    public function new(): ImagorPathBuilder
    {
        return new ImagorPathBuilder($this->baseUrl, $this->signerType, $this->secret, $this->signerTruncate, $this->pathMap);
    }
}
