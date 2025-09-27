<?php

namespace Sandstorm\LaravelImagor;

final readonly class ImagorFactory
{
    /**
     * @internal
     */
    public function __construct(
        private string      $baseUrl,
        private string|null $signerType,
        private string|null $secret,
        private int|null    $signerTruncate,
        /**
         * @var array the key is the original (Laravel) path prefix, the value is the corresponding Imagor path prefix
         */
        private array       $pathMap = [],
    )
    {
    }

    /**
     * @api
     */
    public function new(): Imagor
    {
        return new Imagor($this->baseUrl, $this->signerType, $this->secret, $this->signerTruncate, $this->pathMap);
    }
}
