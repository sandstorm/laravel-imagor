<?php

namespace Imsus\ImgProxy\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Imsus\ImgProxy\ImgProxyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Imsus\\ImgProxy\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            ImgProxyServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('imgproxy.endpoint', 'http://localhost:8080');
        config()->set('imgproxy.key', '9f8872c847aa7692d1ced3cdc65b717029342f01921d7c3cd16a7a7c08bcd2ed');
        config()->set('imgproxy.salt', '1808025a453998c05892b99e08518c5529e8cbd9dc7c2e6d23ca33dfc7db0b30');
    }
}
