<?php

namespace Sandstorm\LaravelImagor;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasRoute('web');
    }

    public function register()
    {
        parent::register();

        // Bind a fresh configured ImagorPathBuilder for each resolution
        $this->app->scoped(ImagorFactory::class, function ($app) {
            return new ImagorFactory(
                baseUrl: config('imagor.base_url'),
                signerType: config('imagor.signer_type', 'sha256'),
                secret: config('imagor.secret'),
                signerTruncate: config('imagor.signer_truncate'),
                pathMap: config('imagor.path_map'),
            );
        });

        $this->loadHelpers();
    }

    protected function loadHelpers(): void
    {
        require_once __DIR__ . '/helpers.php';
    }
}
