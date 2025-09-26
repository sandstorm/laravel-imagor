<?php

use Sandstorm\LaravelImagor\ImagorFactory;
use Sandstorm\LaravelImagor\ImagorPathBuilder;

if (! function_exists('imagor')) {
    function imagor(): ImagorPathBuilder
    {
        return app(ImagorFactory::class)->new();
    }
}
