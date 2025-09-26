<?php

namespace Sandstorm\LaravelImagor\Tests;

use Sandstorm\LaravelImagor\ImagorPathBuilder;
use RuntimeException;

beforeEach(function () {
    $this->sample_image_url = 'https://picsum.photos/seed/sandstorm-laravel/3000/3000.jpg';
    $this->base_url = 'http://localhost:8080';
    $this->secret = 'my-secret-key';
    $this->signer_type = 'sha256';
    $this->signer_truncate = null;
});

// Basic URL Generation Tests
it('can generate signed url with basic resize', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toStartWith($this->base_url . '/');
    expect($url)->not->toContain('unsafe');
    expect($url)->toContain('300x200');
});

it('can generate unsafe url when no secret provided', function () {
    $builder = new ImagorPathBuilder($this->base_url, null, null, null);
    $url = $builder
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toStartWith($this->base_url . '/unsafe/');
    expect($url)->toContain('300x200');
});

it('can generate url with truncated signature', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, 20);
    $url = $builder
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toStartWith($this->base_url . '/');
    expect($url)->not->toContain('unsafe');
    // Signature should be truncated to 20 characters
    $pathParts = explode('/', parse_url($url, PHP_URL_PATH));
    expect(strlen($pathParts[1]))->toBe(20);
});

// Resize Tests
it('can resize with width only', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->resize(width: 300)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('300x0');
});

it('can resize with height only', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->resize(height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('0x200');
});

it('can get resize dimensions', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $builder->resize(width: 300, height: 200);

    expect($builder->getResizeWidth())->toBe(300);
    expect($builder->getResizeHeight())->toBe(200);
});

// Fit-in and Stretch Tests
it('can use fit-in mode', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->fitIn()
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('fit-in');
    expect($url)->toContain('300x200');
});

it('can use stretch mode', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->stretch()
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('stretch');
    expect($url)->toContain('300x200');
});

it('can combine fit-in and stretch', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->fitIn()
        ->stretch()
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('fit-in');
    expect($url)->toContain('stretch');
    expect($url)->toContain('300x200');
});

// Crop Tests
it('can crop image', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->crop(a: 10, b: 10, c: 100, d: 100)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('10x10%3A100x100'); // URL encoded version of 10x10:100x100
});

// Flip Tests
it('can flip horizontally', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->flipHorizontally()
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('300x-200'); // Negative height indicates horizontal flip
});

it('can flip vertically', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->flipVertically()
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('-300x200'); // Negative width indicates vertical flip
});

it('can flip both directions', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->flipHorizontally()
        ->flipVertically()
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('-300x-200'); // Both negative
});

it('can toggle flips', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $builder
        ->flipHorizontally()
        ->flipHorizontally() // Should cancel out
        ->resize(width: 300, height: 200);

    $url = $builder->uriFor($this->sample_image_url);
    expect($url)->toContain('300x200'); // No flip
});

// Trim Test
it('can trim image', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->trim()
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('trim');
});

// Padding Tests
it('can add padding', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->padding(left: 10, top: 20, right: 30, bottom: 40)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('10x20%3A30x40'); // URL encoded version of 10x20:30x40
});

// Alignment Tests
it('can set horizontal alignment', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);

    foreach (['left', 'center', 'right'] as $alignment) {
        $url = $builder->hAlign($alignment)->uriFor($this->sample_image_url);
        expect($url)->toContain($alignment);
    }
});

it('can set vertical alignment', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);

    foreach (['top', 'middle', 'bottom'] as $alignment) {
        $url = $builder->vAlign($alignment)->uriFor($this->sample_image_url);
        expect($url)->toContain($alignment);
    }
});

it('throws exception for invalid horizontal alignment', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);

    expect(function () use ($builder) {
        $builder->hAlign('invalid');
    })->toThrow(RuntimeException::class, 'Unsupported hAlign: invalid');
});

it('throws exception for invalid vertical alignment', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);

    expect(function () use ($builder) {
        $builder->vAlign('invalid');
    })->toThrow(RuntimeException::class, 'Unsupported vAlign: invalid');
});

// Smart Crop Test
it('can use smart crop', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->smart()
        ->resize(width: 300, height: 200)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('smart');
    expect($url)->toContain('300x200');
});

// Filter Tests
it('can add custom filter', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->addFilter('custom', 'arg1', 'arg2')
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('filters%3A');
    expect($url)->toContain('custom%28arg1%2Carg2%29'); // URL encoded custom(arg1,arg2)
});

it('can set quality', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->quality(85)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('quality%2885%29'); // URL encoded quality(85)
});

it('can set format', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);

    foreach (['jpeg', 'png', 'gif', 'webp', 'avif', 'jxl', 'tiff', 'jp2'] as $format) {
        $url = $builder->format($format)->uriFor($this->sample_image_url);
        expect($url)->toContain("format%28{$format}%29"); // URL encoded format(format)
    }
});

it('can set blur with single sigma', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->blur(1.5)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('blur%281.5%29'); // URL encoded blur(1.5)
});

it('can set blur with x and y sigma', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->blur(1.5, 2.0)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('blur%281.5%2C2%29'); // URL encoded blur(1.5,2)
});

it('can set sharpen', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->sharpen(0.5)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('sharpen%280.5%29'); // URL encoded sharpen(0.5)
});

it('can set saturation', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->saturation(120)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('saturation%28120%29'); // URL encoded saturation(120)
});

it('can set brightness', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->brightness(10)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('brightness%2810%29'); // URL encoded brightness(10)
});

it('can set contrast', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->contrast(110)
        ->uriFor($this->sample_image_url);

    expect($url)->toContain('contrast%28110%29'); // URL encoded contrast(110)
});

// Complex Integration Tests
it('can build complex url with multiple transformations', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->trim()
        ->crop(a: 10, b: 10, c: 200, d: 200)
        ->fitIn()
        ->resize(width: 300, height: 200)
        ->hAlign('center')
        ->vAlign('middle')
        ->smart()
        ->quality(85)
        ->format('webp')
        ->blur(1.0)
        ->sharpen(0.5)
        ->brightness(10)
        ->contrast(110)
        ->saturation(120)
        ->uriFor($this->sample_image_url);

    // Should contain all the transformations in correct order
    expect($url)->toContain('trim');
    expect($url)->toContain('10x10%3A200x200'); // crop
    expect($url)->toContain('fit-in');
    expect($url)->toContain('300x200'); // resize
    expect($url)->toContain('center');
    expect($url)->toContain('middle');
    expect($url)->toContain('smart');
    expect($url)->toContain('filters%3A'); // filters section
    expect($url)->toContain('quality%2885%29');
    expect($url)->toContain('format%28webp%29');
});

// Path Order Tests
it('builds path segments in correct order', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->trim()
        ->crop(a: 0, b: 0, c: 100, d: 100)
        ->fitIn()
        ->stretch()
        ->resize(width: 300, height: 200)
        ->padding(left: 10, top: 10, right: 10, bottom: 10)
        ->hAlign('center')
        ->vAlign('middle')
        ->smart()
        ->uriFor($this->sample_image_url);

    // Decode the URL to check order
    $decodedUrl = urldecode($url);
    $pathStart = strpos($decodedUrl, '/trim');
    $cropPos = strpos($decodedUrl, '0x0:100x100', $pathStart);
    $fitInPos = strpos($decodedUrl, 'fit-in', $cropPos);
    $stretchPos = strpos($decodedUrl, 'stretch', $fitInPos);
    $resizePos = strpos($decodedUrl, '300x200', $stretchPos);
    $paddingPos = strpos($decodedUrl, '10x10:10x10', $resizePos);
    $centerPos = strpos($decodedUrl, 'center', $paddingPos);
    $middlePos = strpos($decodedUrl, 'middle', $centerPos);
    $smartPos = strpos($decodedUrl, 'smart', $middlePos);

    expect($pathStart)->toBeLessThan($cropPos);
    expect($cropPos)->toBeLessThan($fitInPos);
    expect($fitInPos)->toBeLessThan($stretchPos);
    expect($stretchPos)->toBeLessThan($resizePos);
    expect($resizePos)->toBeLessThan($paddingPos);
    expect($paddingPos)->toBeLessThan($centerPos);
    expect($centerPos)->toBeLessThan($middlePos);
    expect($middlePos)->toBeLessThan($smartPos);
});

// Edge Cases
it('handles empty resize dimensions', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->resize(width: 0, height: 0)
        ->quality(85)
        ->uriFor($this->sample_image_url);

    // Should not contain resize segment but should contain filters
    expect($url)->not->toContain('0x0');
    expect($url)->toContain('quality%2885%29');
});

it('handles special characters in source URL', function () {
    $specialUrl = 'https://example.com/image with spaces & special chars.jpg';
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->resize(width: 300)
        ->uriFor($specialUrl);

    expect($url)->toStartWith($this->base_url . '/');
    // URL should be double-encoded
    expect($url)->toContain(urlencode(urlencode($specialUrl)));
});

// Method Chaining Tests
it('maintains fluent interface', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);

    $result = $builder
        ->trim()
        ->resize(width: 300)
        ->quality(85);

    expect($result)->toBeInstanceOf(ImagorPathBuilder::class);
});

// Performance Test
it('can generate multiple urls efficiently', function () {
    $start = microtime(true);
    $urls = [];

    for ($i = 0; $i < 100; $i++) {
        $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
        $urls[] = $builder
            ->resize(width: 300 + $i, height: 200 + $i)
            ->quality(80 + ($i % 20))
            ->uriFor($this->sample_image_url);
    }

    $duration = microtime(true) - $start;

    expect(count($urls))->toBe(100);
    expect($duration)->toBeLessThan(2.0); // Should complete reasonably fast
});

// State Isolation Tests
it('maintains independent state between instances', function () {
    $builder1 = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $builder2 = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);

    $url1 = $builder1->resize(width: 300)->uriFor($this->sample_image_url);
    $url2 = $builder2->resize(width: 500)->uriFor($this->sample_image_url);

    expect($url1)->toContain('300x0');
    expect($url2)->toContain('500x0');
    expect($url1)->not->toContain('500x0');
    expect($url2)->not->toContain('300x0');
});

// Default Quality Test
it('automatically adds default quality filter', function () {
    $builder = new ImagorPathBuilder($this->base_url, $this->signer_type, $this->secret, $this->signer_truncate);
    $url = $builder
        ->resize(width: 300)
        ->uriFor($this->sample_image_url);

    // Should contain quality(95) as default
    expect($url)->toContain('quality%2895%29');
});
