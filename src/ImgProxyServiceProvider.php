<?php

namespace Imsus\ImgProxy;

use Imsus\ImgProxy\Commands\ImgProxyCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ImgProxyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-imgproxy')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_imgproxy_table')
            ->hasCommand(ImgProxyCommand::class);
    }
}
