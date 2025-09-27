<?php

namespace Sandstorm\LaravelImagor;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Workbench\App\Console\BenchmarkImageSizes;

/**
 * @internal
 */
class ImagorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-imagor')
            ->hasConfigFile('imagor')
            ->hasConsoleCommand(BenchmarkImageSizes::class)
            ->hasRoute('web');
    }

    public function register()
    {
        parent::register();

        $this->app->scoped(ImagorFactory::class, function ($app) {
            return new ImagorFactory(
                baseUrl: config('imagor.base_url'),
                signerType: config('imagor.signer_type', 'sha256'),
                secret: config('imagor.secret'),
                signerTruncate: config('imagor.signer_truncate'),
                pathMap: config('imagor.path_map'),
            );
        });

        // Bind a fresh configured Imagor for each resolution
        $this->app->bind(Imagor::class, function ($app) {
            return $app->get(ImagorFactory::class)->new();
        });

        $this->loadHelpers();
    }

    protected function loadHelpers(): void
    {
        require_once __DIR__ . '/helpers.php';
    }
}
