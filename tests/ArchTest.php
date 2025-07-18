<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->each->not->toBeUsed();

arch('enums should be immutable')
    ->expect('Imsus\ImgProxy\Enums')
    ->toBeEnums();

arch('facades should extend Laravel facade')
    ->expect('Imsus\ImgProxy\Facades')
    ->toExtend('Illuminate\Support\Facades\Facade');

arch('service providers should extend package service provider')
    ->expect('Imsus\ImgProxy\ImgProxyServiceProvider')
    ->toExtend('Spatie\LaravelPackageTools\PackageServiceProvider');

arch('main classes should have proper method visibility')
    ->expect('Imsus\ImgProxy\ImgProxy')
    ->toHaveMethod('build')
    ->toHaveMethod('url');

arch('no classes should use globals')
    ->expect('Imsus\ImgProxy')
    ->not->toUse(['global', '$_GET', '$_POST', '$_SESSION']);

arch('specific test classes should have Test suffix')
    ->expect(['Imsus\ImgProxy\Tests\ImgProxyTest'])
    ->toHaveSuffix('Test');
