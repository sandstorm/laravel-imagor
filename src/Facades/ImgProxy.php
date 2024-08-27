<?php

namespace Imsus\ImgProxy\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Imsus\ImgProxy\ImgProxy
 */
class ImgProxy extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Imsus\ImgProxy\ImgProxy::class;
    }
}
