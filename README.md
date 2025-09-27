# Laravel integration for Imagor

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sandstorm/laravel-imagor.svg?style=flat-square)](https://packagist.org/packages/sandstorm/laravel-imagor)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/sandstorm/laravel-imagor/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/sandstorm/laravel-imagor/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/sandstorm/laravel-imagor/run-tests.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/sandstorm/laravel-imagor/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sandstorm/laravel-imagor.svg?style=flat-square)](https://packagist.org/packages/sandstorm/laravel-imagor)

A comprehensive Laravel package for [Imagor](https://github.com/cshum/imagor) integration. Generate optimized, signed image URLs with fluent API including resizing, quality control, visual effects, and advanced processing options. Originally forked from https://github.com/imsus/laravel-imgproxy, which deserves most credit :) 

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

This is the contents of the published config file:

```php
return [
    'base_url' => env('IMAGOR_BASE_URL', 'http://localhost:8000'),
    'signer_type' => env('IMAGOR_SIGNER_TYPE', 'sha256'),
    'secret' => env('IMAGOR_SECRET'),
    'signer_truncate' => env('IMAGOR_SIGNER_TRUNCATE'),
];
```

## Setting up Imagor with Docker

This part of the guide assumes you deploy your Laravel application and imagor using Docker.

You can use the [official Imagor Docker image](https://hub.docker.com/r/cshum/imagor) to run Imagor, but this
does NOT contain mozjpeg; so that means the JPEGs could be a bit smaller file-size wise.

**Our recommendation is to use [docker-hub.sandstorm.de/docker-infrastructure/imagor:v1.5.16-mozjpeg](https://docker-hub.sandstorm.de/docker-infrastructure/imagor:v1.5.16-mozjpeg), which contains mozjpeg and is thus outputting smaller JPEG files.**

See https://gitlab.sandstorm.de/docker-infrastructure/imagor/container_registry for the currently built versions.

> if you want to build the image yourself, see see the [./laravel-imagor](./laravel-imagor) folder which contains the sources
> of this docker image.

Mount the `./storage` folder of your Laravel application to the same folder in the Imagor container;
so if the storage folder is located at `/app/storage`, you should mount it to `/app/storage` in the Imagor container
as well.

Then, you can use `FILE_LOADER_BASE_DIR='/app'` to load images from the mounted storage folder.

```
'path_map' => [
    // the key is the original (Laravel) path prefix, the value is the corresponding Imagor path prefix AFTER the FILE_LOADER_BASE_DIR.
    // so /storage on the right side resolves to /app/storage on the file system of the Imagor container
    storage_path() => '/storage',
]
```

## Testing Imagor

you can call the URL [/__imagor-configtest](http://127.0.0.1:8000/__imagor-configtest) to check if Imagor is configured
correctly. This example copies a pre-defined image to the storage folder and then loads it through Imagor to check if
everything is wired correctly.


## Configuration

You can configure the package by updating the values in your `.env` file:

```dotenv
IMAGOR_BASE_URL=http://localhost:8000
IMAGOR_SECRET=your_secret_key_here
IMAGOR_SIGNER_TYPE=sha256
IMAGOR_SIGNER_TRUNCATE=null
```

> [!NOTE]
> The `secret` is required only if you want to generate signed URLs. If you don't want to generate signed URLs, you can leave it empty.

> [!CAUTION]
> Keep your secret key secure and use a strong, randomly generated value in production.

### Configuration Options

| Option             | Description                    | Default               | Options                           |
| ------------------ | ------------------------------ | --------------------- | --------------------------------- |
| `base_url`         | Imagor server URL              | `http://localhost:8000` | Any valid URL                     |
| `secret`           | Signing secret key             | `null`                | Any string                        |
| `signer_type`      | HMAC algorithm                 | `sha256`              | `sha256`, `sha1`, `md5`           |
| `signer_truncate`  | Truncate signature length      | `null`                | Integer or null                   |

## Usage

### Basic Usage

```php
// Generate URL using helper function
$url = imagor()->resize(width: 400, height: 300)->uriFor('https://example.com/image.jpg');

// Or using the fluent API
$url = imagor()
    ->resize(width: 400, height: 300)
    ->uriFor('https://example.com/image.jpg');
```

### Resizing & Cropping

```php
// Basic resizing
$url = imagor()->resize(width: 400, height: 300)->uriFor($imageUrl);

// Fit image within dimensions (preserves aspect ratio)
$url = imagor()->resize(width: 400, height: 300)->fitIn()->uriFor($imageUrl);

// Force stretch to exact dimensions (does NOT preserve aspect ratio)
$url = imagor()->resize(width: 400, height: 300)->stretch()->uriFor($imageUrl);

// Smart cropping with focal point detection
$url = imagor()->resize(width: 400, height: 300)->smart()->uriFor($imageUrl);

// Manual cropping (left, top, right, bottom)
$url = imagor()->crop(10, 10, 300, 200)->uriFor($imageUrl);
```

### Quality & Format Control

```php
// Set JPEG quality
$url = imagor()->resize(width: 400)->quality(85)->uriFor($imageUrl);

// Convert to different formats
$webpUrl = imagor()->resize(width: 400)->format('webp')->uriFor($imageUrl);
$avifUrl = imagor()->resize(width: 400)->format('avif')->uriFor($imageUrl);
$pngUrl = imagor()->resize(width: 400)->format('png')->uriFor($imageUrl);
```

### Visual Effects

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

### Image Transformations

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

### Method Chaining Examples

```php
// Complete image optimization pipeline
$optimizedUrl = imagor()
    ->resize(width: 800, height: 600)
    ->fitIn()
    ->quality(85)
    ->format('webp')
    ->sharpen(1.0)
    ->brightness(5)
    ->contrast(105)
    ->uriFor($originalImage);

// Portrait enhancement
$portraitUrl = imagor()
    ->resize(width: 400, height: 600)
    ->smart()
    ->brightness(10)
    ->contrast(110)
    ->saturation(105)
    ->sharpen(0.8)
    ->quality(90)
    ->uriFor($portrait);
```

## API Reference

### Available Methods

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

### Supported Formats

- `jpeg` - JPEG format
- `png` - PNG format
- `gif` - GIF format
- `webp` - WebP format
- `avif` - AVIF format
- `jxl` - JPEG XL format
- `tiff` - TIFF format
- `jp2` - JPEG 2000 format

## Advanced Usage & Patterns

### Performance Optimization

```php
// Thumbnails - prioritize small file size
$thumbnail = imagor()
    ->resize(width: 150, height: 150)
    ->quality(60)
    ->format('webp')
    ->uriFor($image);

// Hero images - balance quality and size
$hero = imagor()
    ->resize(width: 1920, height: 1080)
    ->fitIn()
    ->quality(85)
    ->format('webp')
    ->uriFor($image);

// Product images - prioritize quality
$product = imagor()
    ->resize(width: 800, height: 600)
    ->smart()
    ->quality(95)
    ->sharpen(0.5)
    ->uriFor($image);
```

### Laravel Integration

#### Blade Directives

Create custom Blade directives for common use cases:

```php
// In AppServiceProvider::boot()
use Illuminate\Support\Facades\Blade;

Blade::directive('imagor', function ($expression) {
    return "<?php echo imagor()->resize(width: 400)->uriFor($expression); ?>";
});

Blade::directive('avatar', function ($expression) {
    return "<?php echo imagor()->resize(width: 150, height: 150)->smart()->uriFor($expression); ?>";
});
```

```blade
{{-- Usage in Blade templates --}}
<img src="@imagor($product->image)" alt="Product">
<img src="@avatar($user->avatar)" alt="User Avatar">
```

#### Eloquent Accessors

Add image processing to Eloquent models:

```php
class User extends Model
{
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return '/default-avatar.png';
        }

        return imagor()
            ->resize(width: 150, height: 150)
            ->smart()
            ->format('webp')
            ->quality(85)
            ->uriFor($this->avatar);
    }

    public function getAvatarThumbnailAttribute(): string
    {
        if (!$this->avatar) {
            return '/default-avatar.png';
        }

        return imagor()
            ->resize(width: 50, height: 50)
            ->smart()
            ->quality(70)
            ->uriFor($this->avatar);
    }
}
```

#### API Resources

Use in API resources for consistent image URLs:

```php
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => [
                'small' => imagor()->resize(width: 50, height: 50)->uriFor($this->avatar),
                'medium' => imagor()->resize(width: 150, height: 150)->uriFor($this->avatar),
                'large' => imagor()->resize(width: 300, height: 300)->uriFor($this->avatar),
            ],
        ];
    }
}
```

### Common Patterns

#### Responsive Images

Generate multiple image sizes for responsive images:

```php
class ResponsiveImage
{
    public static function generateSrcset(string $imageUrl, array $sizes): array
    {
        $srcset = [];

        foreach ($sizes as $width) {
            $url = imagor()
                ->resize(width: $width, height: intval($width * 0.75)) // 4:3 aspect ratio
                ->smart()
                ->format('webp')
                ->quality(85)
                ->uriFor($imageUrl);

            $srcset[] = "{$url} {$width}w";
        }

        return $srcset;
    }
}

// Usage
$sizes = [400, 800, 1200, 1600];
$srcset = ResponsiveImage::generateSrcset($image, $sizes);
$srcsetString = implode(', ', $srcset);
```

```blade
<img src="{{ imagor()->resize(width: 800)->uriFor($image) }}"
     srcset="{{ $srcsetString }}"
     sizes="(max-width: 768px) 100vw, 50vw"
     alt="Responsive image">
```

### Image Processing Recipes

#### Photo Enhancement

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

#### E-commerce Optimization

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

## Testing

### Unit & Integration Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage
```

### Interactive Testing with Workbench

The package includes a comprehensive workbench environment for interactive testing:

```bash
# Start the workbench server
composer start

# Or build separately and serve
composer build
php vendor/bin/testbench serve
```

Once the server is running (typically at `http://localhost:8000`), you can access:

#### API Test Endpoints

- **Test Overview**: `http://localhost:8000/imgproxy-test/` - JSON overview of all available tests
- **Basic Test**: `http://localhost:8000/imgproxy-test/basic` - Basic URL generation testing
- **Effects Test**: `http://localhost:8000/imgproxy-test/effects` - Quality and visual effects testing
- **Formats Test**: `http://localhost:8000/imgproxy-test/formats` - Format conversion testing
- **Resize Test**: `http://localhost:8000/imgproxy-test/resize` - Different resize types comparison
- **Quality Test**: `http://localhost:8000/imgproxy-test/quality` - Quality comparison testing
- **Visual Effects**: `http://localhost:8000/imgproxy-test/visual-effects` - Visual effects testing
- **Complex Processing**: `http://localhost:8000/imgproxy-test/complex` - Complex processing testing

#### Visual Testing

- **Visual Test Suite**: `http://localhost:8000/imgproxy-visual-test` - Complete browser-based visual testing

The visual test page includes:
- **Real Image Processing** - See actual Imagor results with sample images
- **Quality Comparison** - Side-by-side quality levels (30%, 70%, 95%)
- **Format Comparison** - Visual differences between JPEG, PNG, WebP, AVIF
- **Resize Types Demo** - Visual behavior of fit, fill, force, smart modes
- **Effects Showcase** - Blur, sharpen, saturation, brightness effects
- **Complex Processing** - Portrait enhancement and vintage effects


## Troubleshooting

### Common Issues

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

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Imam Susanto](https://github.com/imsus) for the original package
- [Sandstorm Media](https://github.com/sandstorm) for the Imagor fork
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
