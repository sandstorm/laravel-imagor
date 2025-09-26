<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->each->not->toBeUsed();

arch('enums should be immutable')
    ->expect('Sandstorm\LaravelImagor\Enums')
    ->toBeEnums();

arch('facades should extend Laravel facade')
    ->expect('Sandstorm\LaravelImagor\Facades')
    ->toExtend('Illuminate\Support\Facades\Facade');

arch('service providers should extend package service provider')
    ->expect('Sandstorm\LaravelImagor\ImgProxyServiceProvider')
    ->toExtend('Spatie\LaravelPackageTools\PackageServiceProvider');

arch('main classes should have proper method visibility')
    ->expect('Sandstorm\LaravelImagor\ImgProxy')
    ->toHaveMethod('build')
    ->toHaveMethod('url');

arch('no classes should use globals')
    ->expect('Sandstorm\LaravelImagor')
    ->not->toUse(['global', '$_GET', '$_POST', '$_SESSION']);

arch('specific test classes should have Test suffix')
    ->expect(['Sandstorm\LaravelImagor\Tests\ImgProxyTest'])
    ->toHaveSuffix('Test');
