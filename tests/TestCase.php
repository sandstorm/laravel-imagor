<?php

namespace Imsus\ImgProxy\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Imsus\ImgProxy\ImgProxyServiceProvider;

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
        config()->set('database.default', 'testing');

        config()->set('imgproxy.endpoint', 'http://localhost:8080');
        config()->set('imgproxy.key', '');
        config()->set('imgproxy.salt', '');
        config()->set('imgproxy.default_preset', 'default');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-imgproxy_table.php.stub';
        $migration->up();
        */
    }
}
