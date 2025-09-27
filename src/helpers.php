<?php

use Sandstorm\LaravelImagor\ImagorFactory;
use Sandstorm\LaravelImagor\Imagor;

if (! function_exists('imagor')) {
    function imagor(): Imagor
    {
        return app(ImagorFactory::class)->new();
    }
}
