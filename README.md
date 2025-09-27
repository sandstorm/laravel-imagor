# Laravel integration for Imagor

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sandstorm/laravel-imagor.svg?style=flat-square)](https://packagist.org/packages/sandstorm/laravel-imagor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sandstorm/laravel-imagor/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sandstorm/laravel-imagor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sandstorm/laravel-imagor/run-tests.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sandstorm/laravel-imagor/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sandstorm/laravel-imagor.svg?style=flat-square)](https://packagist.org/packages/sandstorm/laravel-imagor)

A comprehensive Laravel package for [Imagor](https://github.com/cshum/imagor) integration. Generate optimized, signed image URLs with fluent API including resizing, quality control, visual effects, and advanced processing options. Originally forked from https://github.com/imsus/laravel-imgproxy, which deserves most credit :) 

<!-- TOC -->
* [Laravel integration for Imagor](#laravel-integration-for-imagor)
  * [Features](#features)
  * [Installation](#installation)
* [Basic Usage](#basic-usage)
  * [Accessing the Imagor object](#accessing-the-imagor-object)
  * [Resizing & Cropping](#resizing--cropping)
  * [Quality & Format Control](#quality--format-control)
  * [Visual Effects](#visual-effects)
  * [Image Transformations](#image-transformations)
* [Laravel Integration](#laravel-integration)
  * [Livewire: Display temporary uploaded files](#livewire-display-temporary-uploaded-files)
  * [Display images from the public storage](#display-images-from-the-public-storage)
  * [Upload images in Filament Forms](#upload-images-in-filament-forms)
  * [Blade Directives](#blade-directives)
* [API Reference](#api-reference)
  * [Available Methods](#available-methods)
  * [Supported Formats](#supported-formats)
* [Image Processing Recipes](#image-processing-recipes)
  * [Photo Enhancement](#photo-enhancement)
  * [E-commerce Optimization](#e-commerce-optimization)
* [Development Setup](#development-setup)
  * [Unit & Integration Tests](#unit--integration-tests)
  * [Interactive Testing with Workbench](#interactive-testing-with-workbench)
* [Troubleshooting](#troubleshooting)
* [File Sizes](#file-sizes)
* [Changelog](#changelog)
* [Credits](#credits)
* [License](#license)
<!-- TOC -->

## Features

- 🚀 **Fluent API** - Clean, chainable method syntax
- 🔒 **Secure URLs** - HMAC signed URLs with configurable keys
- 🎨 **Visual Effects** - Blur, sharpen, brightness, contrast, saturation adjustments
- ⚡ **Quality Control** - Fine-tune compression and output formats
- 🔧 **Flexible Resizing** - Multiple resize modes with smart cropping
- 🧩 **Laravel Integration** - Service provider, facade, and helper function
- ✅ **Type Safe** - PHP 8.2+ with comprehensive validation
- 🧪 **Well Tested** - Comprehensive test suite with workbench integration

## Installation

You can install the package via composer:

```bash
composer require sandstorm/laravel-imagor
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="imagor-config"
```

To set up Imagor via `docker-compose.yaml`, use the following snippet:

```yaml
services:
  laravel:
    # ...
    # we assume the laravel application lives in "/app" in the Docker container
    volumes:
      - laravel-storage:/app/storage
    environment:
      IMAGOR_BASE_URL: http://imagor:8091
      IMAGOR_SECRET: UNSAFE_DEV_SECRET
      IMAGOR_SIGNER_TYPE: sha256
      IMAGOR_SIGNER_TRUNCATE: 40

  # use the mozjpeg version from docker-hub.sandstorm.de/docker-infrastructure/imagor:v1.5.16-mozjpeg
  # See https://gitlab.sandstorm.de/docker-infrastructure/imagor/container_registry for the currently built versions.
  #
  # to get bigger JPEG files - alternatively you can also use the official shumc/imagor image from docker hub
  imagor:
    image: docker-hub.sandstorm.de/docker-infrastructure/imagor:v1.5.16-mozjpeg
    ports:
      - ${IMAGOR_PORT:-8091:8091}
    environment:
      # if things do not work, enable debugging:
      # DEBUG: 1
      PORT: 8091

      IMAGOR_SECRET: UNSAFE_DEV_SECRET
      IMAGOR_SIGNER_TYPE: sha256
      IMAGOR_SIGNER_TRUNCATE: 40

      IMAGOR_CACHE_HEADER_TTL: 8760h
      IMAGOR_PROCESS_CONCURRENCY: 25
      IMAGOR_PROCESS_QUEUE_SIZE: 200
      SERVER_ADDRESS: imagor
      SERVER_CORS: true

      # crucial if files contain special characters, e.g. for Laravel temporary uploads
      # -> disables mangling of filenames during loading
      FILE_SAFE_CHARS: '--'
      FILE_LOADER_BASE_DIR: '/app'

      FILE_RESULT_STORAGE_BASE_DIR: '/mnt/imagor_cache/results'
      FILE_STORAGE_BASE_DIR: '/mnt/imagor_cache/input'
      VIPS_MAX_ANIMATION_FRAMES: 1
      VIPS_MAX_FILTER_OPS: 20
      VIPS_MAX_WIDTH: 10000
      VIPS_MAX_HEIGHT: 10000
      VIPS_MAX_RESOLUTION: 100000000

      # bigger images (optional)
      VIPS_MOZJPEG: 1
    volumes:
      - laravel-storage:/app/storage

```

Finally, can call the URL [/__imagor-configtest](http://127.0.0.1:8080/__imagor-configtest) to check if Imagor is configured
correctly. This example copies some pre-defined images to the storage folder and then loads it through Imagor to check if
everything is wired correctly.


<summary>
Setup Details of the Docker setup:

<details>

> if you want to build the image yourself, see see the [./laravel-imagor](./laravel-imagor) folder which contains the sources
> of this docker image.

Mount the `./storage` folder of your Laravel application to the same folder in the Imagor container;
so if the storage folder is located at `/app/storage`, you should mount it to `/app/storage` in the Imagor container
as well.

Then, you can use `FILE_LOADER_BASE_DIR='/app'` to load images from the mounted storage folder.

Adjust the path mapping if needed via the `path_map` config option:

```
'path_map' => [
    // the key is the original (Laravel) path prefix, the value is the corresponding Imagor path prefix AFTER the FILE_LOADER_BASE_DIR.
    // so /storage on the right side resolves to /app/storage on the file system of the Imagor container
    storage_path() => '/storage',
]
```


</details>
</summary>


# Basic Usage

```php
// Generate URL using helper function
$url = imagor()
    ->resize(width: 400, height: 300)
    ->uriFor('https://example.com/image.jpg');
```

NOTE: the image URL to be processed is passed at the END of the chain, to be able to use the same instance of the
`Imagor` class for multiple image processing operations:

```php
$resizeOp = imagor()
    ->resize(width: 400, height: 300);
    

$url1 = $resizeOp->uriFor('https://example.com/image.jpg');
$url2 = $resizeOp->uriFor('https://example.com/foo.jpg');
```

NOTE: the `Imagor` class is **immutable**, so you always need to assign the result of a method call to a variable:

```php
$imagor = imagor();

// ❌ WILL NOT WORK ❌ because a new object is returned
$imagor->resize(width: 400, height: 300);
$imagor->uriFor('https://example.com/image.jpg');

// ✅ Instead, do the following:
$imagor = $imagor->resize(width: 400, height: 300);
$imagor->uriFor('https://example.com/image.jpg');
```


## Accessing the Imagor object

The following methods exist for accessing the Imagor object:

- inject `Sandstorm\LaravelImagor\ImagorFactory`, and call `->new()` to get a new instance of the `Imagor` class
- inject `Sandstorm\LaravelImagor\Imagor` - you'll get a new instance of the `Imagor` class every time
- use the `imagor()` helper function

If in doubt, use one of the injections.

## Resizing & Cropping

```php
// Basic resizing
$url = imagor()
    ->resize(width: 400, height: 300)
    ->uriFor($imageUrl);

// Fit image within dimensions (preserves aspect ratio)
$url = imagor()
    ->resize(width: 400, height: 300)
    ->fitIn()
    ->uriFor($imageUrl);

// Force stretch to exact dimensions (does NOT preserve aspect ratio)
$url = imagor()
    ->resize(width: 400, height: 300)
    ->stretch()
    ->uriFor($imageUrl);

// Smart cropping with focal point detection
$url = imagor()
    ->resize(width: 400, height: 300)
    ->smart()
    ->uriFor($imageUrl);

// Manual cropping (left, top, right, bottom)
$url = imagor()
    ->crop(10, 10, 300, 200)
    ->uriFor($imageUrl);
```

## Quality & Format Control

```php
// Set JPEG quality
$url = imagor()->resize(width: 400)->quality(85)->uriFor($imageUrl);

// Convert to different formats
$webpUrl = imagor()->resize(width: 400)->format('webp')->uriFor($imageUrl);
$avifUrl = imagor()->resize(width: 400)->format('avif')->uriFor($imageUrl);
$pngUrl = imagor()->resize(width: 400)->format('png')->uriFor($imageUrl);
```

## Visual Effects

```php
$url = imagor()
    ->resize(width: 500, height: 300)
    ->blur(2.0)              // Blur effect
    ->sharpen(1.5)           // Sharpen details  
    ->brightness(20)         // Increase brightness
    ->contrast(110)          // Enhance contrast (percentage)
    ->saturation(120)        // Boost saturation (percentage)
    ->uriFor($imageUrl);
```

## Image Transformations

```php
// Flip images
$url = imagor()
    ->resize(width: 400, height: 300)
    ->flipHorizontally()
    ->flipVertically() 
    ->uriFor($imageUrl);

// Add padding (left, top, right, bottom)
$url = imagor()
    ->resize(width: 400, height: 300)
    ->padding(10, 10, 10, 10)
    ->uriFor($imageUrl);

// Set alignment for cropping
$url = imagor()
    ->resize(width: 400, height: 300)
    ->hAlign('left')         // 'left', 'right', 'center'
    ->vAlign('top')          // 'top', 'bottom', 'middle'
    ->uriFor($imageUrl);
```

# Laravel Integration

## Livewire: Display temporary uploaded files

before:

```blade
<img width="300" src="{{ $mediaFile->temporaryUrl() }}" />
```

after:

```blade
<img width="300" src="{{ imagor()->resize(300)->uriFor($mediaFile->getPathname()) }}" />
```

## Display images from the public storage

before: 

```blade
<img src="{{ asset('storage/' . $post->media_files[0]) }}" />
```

after:

```blade
<img src="{{ imagor()->resize(300)->uriFor(Storage::disk('public')->path($post->media_files[0])) }}">
```

TODO: see if we can make this work a bit simpler :)

## Upload images in Filament Forms

before:

```php
FileUpload::make('media_files')
    ->acceptedFileTypes(['image/*'])
    ->rules(['image', 'max:10240']) // max 10MB per file
    ->disk('public')
    ->visibility('public')
```

after:

```php
use Sandstorm\LaravelImagor\Filament\Components\ImagorFileUpload;

ImagorFileUpload::make('media_files')
    ->acceptedFileTypes(['image/*'])
    ->rules(['image', 'max:10240']) // max 10MB per file
    
    // not required anymore, imagor also works with private files
    ->disk('public')
    // not required anymore, imagor also works with private files
    ->visibility('public')
    
    // specify which size is needed
    ->imageProcessor(imagor()->resize(100)),
    
    // same logic, different syntax (without global function)
    ->imageProcessor(fn(ImagorPathBuilder $imagor) => $imagor->resize(100)),
```

## Blade Directives

Create custom Blade directives for common use cases:

```php
// In AppServiceProvider::boot()
use Illuminate\Support\Facades\Blade;

Blade::directive('imagorWide', function ($expression) {
    return "<?php echo imagor()->resize(width: 400)->uriFor($expression); ?>";
});

Blade::directive('avatar', function ($expression) {
    return "<?php echo imagor()->resize(width: 150, height: 150)->smart()->uriFor($expression); ?>";
});
```

```blade
{{-- Usage in Blade templates --}}
<img src="@imagorWide($product->image)" alt="Product">
<img src="@avatar($user->avatar)" alt="User Avatar">
```

# API Reference

## Available Methods

| Method                                          | Parameters              | Description                           |
| ----------------------------------------------- | ----------------------- | ------------------------------------- |
| `resize(int $width, int $height)`               | Width, height in pixels | Set image dimensions                  |
| `crop(int $a, int $b, int $c, int $d)`          | Coordinates             | Manual crop (left, top, right, bottom) |
| `fitIn()`                                       | -                       | Fit image within dimensions           |
| `stretch()`                                     | -                       | Force resize without aspect ratio     |
| `smart()`                                       | -                       | Smart focal point detection           |
| `trim()`                                        | -                       | Remove surrounding whitespace         |
| `flipHorizontally()`                            | -                       | Flip image horizontally               |
| `flipVertically()`                              | -                       | Flip image vertically                 |
| `padding(int $left, int $top, int $right, int $bottom)` | Padding values          | Add padding around image              |
| `hAlign(string $align)`                         | 'left', 'right', 'center' | Horizontal alignment                  |
| `vAlign(string $align)`                         | 'top', 'bottom', 'middle' | Vertical alignment                    |
| `quality(int $quality)`                         | 0-100                   | Set JPEG quality                      |
| `format(string $format)`                        | Format string           | Set output format                     |
| `blur(float $sigma)`                            | ≥0.0                    | Apply blur effect                     |
| `sharpen(float $sigma)`                         | ≥0.0                    | Apply sharpen effect                  |
| `brightness(int $amount)`                       | -255 to 255             | Adjust brightness                     |
| `contrast(int $amount)`                         | Percentage              | Adjust contrast                       |
| `saturation(int $amount)`                       | Percentage              | Adjust saturation                     |
| `addFilter(string $name, ...$args)`             | Filter name and args    | Add custom filter                     |
| `uriFor(string $sourceImage)`                    | Image URL               | Generate final URL                    |

## Supported Formats

- `jpeg` - JPEG format
- `png` - PNG format
- `gif` - GIF format
- `webp` - WebP format
- `avif` - AVIF format
- `jxl` - JPEG XL format
- `tiff` - TIFF format
- `jp2` - JPEG 2000 format


# Image Processing Recipes

## Photo Enhancement

```php
// Portrait enhancement
$enhancedPortrait = imagor()
    ->resize(width: 600, height: 800)
    ->smart()
    ->brightness(8)       // Slightly brighter
    ->contrast(110)       // Enhanced contrast
    ->saturation(105)     // Subtle saturation boost
    ->sharpen(0.8)        // Gentle sharpening
    ->quality(92)
    ->uriFor($portrait);

// Vintage effect
$vintageEffect = imagor()
    ->resize(width: 600, height: 400)
    ->saturation(70)      // Reduced saturation
    ->contrast(90)        // Lower contrast
    ->brightness(-10)     // Slightly darker
    ->quality(85)
    ->uriFor($image);
```

## E-commerce Optimization

```php
// Clean product photos
$productClean = imagor()
    ->resize(width: 800, height: 800)
    ->fitIn()
    ->trim()              // Remove whitespace
    ->brightness(15)      // Bright and clean
    ->contrast(110)       // Good contrast
    ->sharpen(1.5)        // Sharp product details
    ->quality(95)         // High quality for products
    ->format('webp')
    ->uriFor($product);
```

# Development Setup

## Unit & Integration Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage
```

## Interactive Testing with Workbench

The package includes a comprehensive workbench environment for interactive testing:

```bash
# Start the workbench server
composer start

# Or build separately and serve
composer build
php vendor/bin/testbench serve
```

Now access the **Visual Test Suite** at http://localhost:8000/imgproxy-visual-test

# Troubleshooting

**Problem**: Getting "unsafe" URLs instead of signed URLs

```
http://localhost:8000/unsafe/400x300/...
```

**Solution**: Ensure `IMAGOR_SECRET` is set in your `.env` file.

**Problem**: Images not loading/404 errors
**Solutions**:

- Verify Imagor server is running at the configured base URL
- Check source image URLs are accessible
- Ensure Imagor server can reach source URLs (firewall/network issues)

**Problem**: Poor image quality  
**Solutions**:

- Increase quality setting: `->quality(90)`
- Use appropriate output format: `->format('webp')`
- Avoid excessive sharpening: `->sharpen(1.0)` instead of higher values

# File Sizes

**imagor_docker, with VIPS_MOZJPEG: 0; or shumc/imagor:latest**

(no mozjpeg)

```bash

docker compose down -v
docker compose up -d

+---------------------+--------+------------+----------+
| version             | format | dimensions | size     |
+---------------------+--------+------------+----------+
| original            | jpeg   | orig       | 6.3 MB   |
| optimized_no_change | jpeg   | orig       | 3.7 MB   |
| optimized_quality95 | jpeg   | orig       | 13.8 MB  |
| optimized_quality80 | jpeg   | orig       | 4.3 MB   |
| optimized_quality50 | jpeg   | orig       | 2.5 MB   |
| optimized_no_change | jpeg   | 600x600    | 44.3 KB  |
| optimized_quality95 | jpeg   | 600x600    | 161.9 KB |
| optimized_quality80 | jpeg   | 600x600    | 50.8 KB  |
| optimized_quality50 | jpeg   | 600x600    | 29.7 KB  |
| optimized_no_change | webp   | orig       | 1.5 MB   |
| optimized_quality95 | webp   | orig       | 6.2 MB   |
| optimized_quality80 | webp   | orig       | 1.9 MB   |
| optimized_quality50 | webp   | orig       | 1.1 MB   |
| optimized_no_change | webp   | 600x600    | 28.6 KB  |
| optimized_quality95 | webp   | 600x600    | 97.4 KB  |
| optimized_quality80 | webp   | 600x600    | 35.6 KB  |
| optimized_quality50 | webp   | 600x600    | 20.5 KB  |
+---------------------+--------+------------+----------+
```

**imagor_docker, with VIPS_MOZJPEG: 1**

f.e. the image ghcr.io/cshum/imagor-mozjpeg:docker-variants from https://github.com/cshum/imagor/issues/456#issuecomment-3341418493

```bash
docker compose down -v
docker compose up -d

vendor/bin/testbench  imagor:benchmark-image-sizes
+---------------------+--------+------------+----------+
| version             | format | dimensions | size     | without mozjpeg (from above)
+---------------------+--------+------------+----------+
| original            | jpeg   | orig       | 6.3 MB   |
| optimized_no_change | jpeg   | orig       | 2.5 MB   | 3.7 MB (32.4% bigger)
| optimized_quality95 | jpeg   | orig       | 10.6 MB  | 13.8 MB (23.2% bigger)
| optimized_quality80 | jpeg   | orig       | 3.1 MB   | 4.3 MB (27.9% bigger)
| optimized_quality50 | jpeg   | orig       | 1.5 MB   | 2.5 MB (40.0% bigger)
| optimized_no_change | jpeg   | 600x600    | 35.9 KB  | 44.3 KB (19.0% bigger)
| optimized_quality95 | jpeg   | 600x600    | 152.2 KB | 161.9 KB (6.0% bigger)
| optimized_quality80 | jpeg   | 600x600    | 42.8 KB  | 50.8 KB (15.7% bigger)
| optimized_quality50 | jpeg   | 600x600    | 22.1 KB  | 29.7 KB (25.6% bigger)
| optimized_no_change | webp   | orig       | 1.5 MB   |
| optimized_quality95 | webp   | orig       | 6.2 MB   |
| optimized_quality80 | webp   | orig       | 1.9 MB   |
| optimized_quality50 | webp   | orig       | 1.1 MB   |
| optimized_no_change | webp   | 600x600    | 28.6 KB  |
| optimized_quality95 | webp   | 600x600    | 97.4 KB  |
| optimized_quality80 | webp   | 600x600    | 35.6 KB  |
| optimized_quality50 | webp   | 600x600    | 20.5 KB  |
+---------------------+--------+------------+----------+
```



# Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

# Credits

- [Imam Susanto](https://github.com/imsus) for the original package
- [Sandstorm Media](https://github.com/sandstorm) for the Imagor fork
- [All Contributors](../../contributors)

# License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
