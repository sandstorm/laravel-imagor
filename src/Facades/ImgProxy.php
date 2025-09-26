<?php

namespace Sandstorm\LaravelImagor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sandstorm\LaravelImagor\ImgProxy
 */
class ImgProxy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Sandstorm\LaravelImagor\ImgProxy::class;
    }
}
