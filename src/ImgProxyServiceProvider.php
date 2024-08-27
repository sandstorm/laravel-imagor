<?php

namespace Imsus\ImgProxy;

use Imsus\ImgProxy\Commands\ImgProxyCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ImgProxyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-imgproxy')
            ->hasConfigFile();
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(ImgProxy::class, function ($app) {
            return new ImgProxy();
        });

        $this->loadHelpers();
    }

    protected function loadHelpers()
    {
        require_once __DIR__ . '/helpers.php';
    }
}
