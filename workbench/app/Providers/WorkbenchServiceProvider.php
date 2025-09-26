<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Sandstorm\LaravelImagor\Enums\OutputExtension;
use Sandstorm\LaravelImagor\Enums\ResizeType;
use Sandstorm\LaravelImagor\Facades\ImgProxy;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->bootRoutes();
    }

    /**
     * Bootstrap workbench routes for testing ImgProxy functionality.
     */
    private function bootRoutes(): void
    {
        Route::prefix('imgproxy-test')->group(function () {
            // Basic URL generation test
            Route::get('/basic', function () {
                $imageUrl = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000';

                $processedUrl = imagor()->resize(width: 400, height: 300)->uriFor($imageUrl);

                return response()->json([
                    'original' => $imageUrl,
                    'processed' => $processedUrl,
                    'test' => 'basic_url_generation',
                ]);
            });

            // Quality and effects test
            Route::get('/effects', function () {
                $imageUrl = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000';

                $processedUrl = imagor()
                    ->resize(width: 500, height: 400)
                    ->quality(85)
                    ->blur(1.0)
                    ->sharpen(0.5)
                    ->brightness(10)
                    ->contrast(1.1)
                    ->saturation(1.05)
                    ->uriFor($imageUrl);

                return response()->json([
                    'original' => $imageUrl,
                    'processed' => $processedUrl,
                    'effects_applied' => [
                        'quality' => 85,
                        'blur' => 1.0,
                        'sharpen' => 0.5,
                        'brightness' => 10,
                        'contrast' => 1.1,
                        'saturation' => 1.05,
                    ],
                    'test' => 'effects_and_quality',
                ]);
            });

            // Format conversion test
            Route::get('/formats', function () {
                $imageUrl = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000';

                $formats = [
                    'jpeg' => imagor()->resize(width: 250)->format('jpeg')->uriFor($imageUrl),
                    'png' => imagor()->resize(width: 250)->format('png')->uriFor($imageUrl),
                    'webp' => imagor()->resize(width: 250)->format('webp')->uriFor($imageUrl),
                    'avif' => imagor()->resize(width: 250)->format('avif')->uriFor($imageUrl),
                ];

                return response()->json([
                    'original' => $imageUrl,
                    'formats' => $formats,
                    'test' => 'format_conversion',
                ]);
            });

            // Resize types test
            Route::get('/resize', function () {
                $imageUrl = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000';

                $resizeTypes = [
                    'fit' => imagor()->resize(width: 300, height: 200)->fitIn()->uriFor($imageUrl),
                    'fill' => imagor()->resize(width: 300, height: 200)->uriFor($imageUrl),
                    'force' => imagor()->resize(width: 300, height: 200)->stretch()->uriFor($imageUrl),
                    'auto' => imagor()->resize(width: 300, height: 200)->smart()->uriFor($imageUrl),
                ];

                return response()->json([
                    'original' => $imageUrl,
                    'resize_types' => $resizeTypes,
                    'test' => 'resize_types',
                ]);
            });

            // Quality comparison test
            Route::get('/quality', function () {
                $imageUrl = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000';

                $qualities = [
                    'low' => imagor()->resize(width: 300, height: 200)->quality(30)->uriFor($imageUrl),
                    'medium' => imagor()->resize(width: 300, height: 200)->quality(70)->uriFor($imageUrl),
                    'high' => imagor()->resize(width: 300, height: 200)->quality(95)->uriFor($imageUrl),
                ];

                return response()->json([
                    'original' => $imageUrl,
                    'qualities' => $qualities,
                    'test' => 'quality_comparison',
                ]);
            });

            // Visual effects test
            Route::get('/visual-effects', function () {
                $imageUrl = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000';

                $effects = [
                    'blur' => imagor()->resize(width: 300)->blur(2.0)->uriFor($imageUrl),
                    'sharpen' => imagor()->resize(width: 300)->sharpen(2.0)->uriFor($imageUrl),
                    'saturated' => imagor()->resize(width: 300)->saturation(2.0)->uriFor($imageUrl),
                ];

                return response()->json([
                    'original' => $imageUrl,
                    'effects' => $effects,
                    'test' => 'visual_effects',
                ]);
            });

            // Complex processing test
            Route::get('/complex', function () {
                $imageUrl = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000';

                $complex = [
                    'portrait_enhanced' => imagor()
                        ->resize(width: 300, height: 400)
                        ->brightness(10)
                        ->contrast(1.1)
                        ->saturation(1.05)
                        ->sharpen(0.8)
                        ->quality(92)
                        ->uriFor($imageUrl),
                    'vintage_effect' => imagor()
                        ->resize(width: 300, height: 400)
                        ->saturation(0.7)
                        ->contrast(0.9)
                        ->brightness(-10)
                        ->quality(85)
                        ->uriFor($imageUrl),
                ];

                return response()->json([
                    'original' => $imageUrl,
                    'complex_processing' => $complex,
                    'test' => 'complex_processing',
                ]);
            });

            // Test index with all available tests
            Route::get('/', function () {
                return response()->json([
                    'message' => 'ImgProxy Laravel Package Test Suite',
                    'available_tests' => [
                        '/imgproxy-test/basic' => 'Basic URL generation',
                        '/imgproxy-test/effects' => 'Quality and effects testing',
                        '/imgproxy-test/formats' => 'Format conversion testing',
                        '/imgproxy-test/resize' => 'Resize types testing',
                        '/imgproxy-test/error-handling' => 'Error handling testing',
                        '/imgproxy-test/performance' => 'Performance testing',
                        '/imgproxy-test/quality' => 'Quality comparison testing',
                        '/imgproxy-test/visual-effects' => 'Visual effects testing',
                        '/imgproxy-test/complex' => 'Complex processing testing',
                    ],
                    'usage' => 'Visit any of the test endpoints to see ImgProxy in action',
                    'visual_tests' => 'Visit /imgproxy-visual-test for browser-based visual testing',
                ]);
            });
        });

        // Visual test page route
        Route::get('/imgproxy-visual-test', function () {
            return view('imgproxy-test');
        });
    }
}
