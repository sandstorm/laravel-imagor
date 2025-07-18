# ImgProxy Laravel Package - Workbench Testing

This workbench provides a complete testing environment for the ImgProxy Laravel package, including API endpoints, visual tests, and integration testing.

## Quick Start

### 1. Start the Workbench Server

```bash
# Build and start the workbench
composer start

# Or build separately and then start
composer build
php vendor/bin/testbench serve
```

### 2. Access Test Endpoints

Once the server is running (typically at `http://localhost:8000`), you can access:

#### API Test Endpoints
- **Test Index**: `http://localhost:8000/imgproxy-test/` - JSON overview of all tests
- **Basic Test**: `http://localhost:8000/imgproxy-test/basic` - Basic URL generation
- **Effects Test**: `http://localhost:8000/imgproxy-test/effects` - Quality and visual effects
- **Formats Test**: `http://localhost:8000/imgproxy-test/formats` - Format conversion (JPEG, PNG, WebP, AVIF)
- **Resize Test**: `http://localhost:8000/imgproxy-test/resize` - Different resize types
- **Facade vs Helper**: `http://localhost:8000/imgproxy-test/facade-vs-helper` - Compare facade and helper
- **Config Test**: `http://localhost:8000/imgproxy-test/config` - Configuration testing
- **Error Handling**: `http://localhost:8000/imgproxy-test/error-handling` - Error scenarios
- **Performance Test**: `http://localhost:8000/imgproxy-test/performance` - Performance benchmarks

#### Visual Test Page
- **Visual Tests**: `http://localhost:8000/imgproxy-visual-test` - Browser-based visual testing with real images

### 3. Run Integration Tests

```bash
# Run all tests including workbench integration tests
composer test

# Run only workbench integration tests
composer test --filter=WorkbenchIntegrationTest
```

## What Gets Tested

### API Endpoints Testing
- ✅ **Basic URL Generation** - Width/height setting and URL building
- ✅ **Quality & Effects** - Quality, blur, sharpen, brightness, contrast, saturation
- ✅ **Format Conversion** - JPEG, PNG, WebP, AVIF output formats
- ✅ **Resize Types** - Fit, fill, force, auto resize modes
- ✅ **Facade vs Helper** - Ensures both approaches produce identical results
- ✅ **Configuration** - Validates all config values are accessible
- ✅ **Error Handling** - Invalid URLs and parameter validation
- ✅ **Performance** - URL generation speed (100 URLs benchmark)

### Visual Testing
- ✅ **Real Image Processing** - Uses actual images from Picsum
- ✅ **Quality Comparison** - Side-by-side quality comparisons (30%, 70%, 95%)
- ✅ **Format Comparison** - Visual format differences (JPEG, PNG, WebP, AVIF)
- ✅ **Resize Types** - Visual resize behavior comparison
- ✅ **Visual Effects** - Blur, sharpen, saturation effects
- ✅ **Complex Processing** - Portrait enhancement and vintage effects
- ✅ **High DPI Support** - Standard vs 2x DPI comparison

### Integration Testing
- ✅ **Service Provider Registration** - Package properly bootstraps in Laravel
- ✅ **Facade Registration** - ImgProxy facade is available
- ✅ **Helper Function** - Global `imgproxy()` function works
- ✅ **Configuration Loading** - All config values load correctly
- ✅ **Enum Classes** - All enum classes are accessible
- ✅ **HTTP Requests** - All endpoints return expected responses
- ✅ **Error Scenarios** - Invalid inputs are handled gracefully

## Configuration

The workbench uses the configuration file at `workbench/config/imgproxy.php`:

```php
return [
    'endpoint' => env('IMGPROXY_ENDPOINT', 'http://localhost:8080'),
    'key' => env('IMGPROXY_KEY'),
    'salt' => env('IMGPROXY_SALT'),
    'default_source_url_mode' => env('IMGPROXY_DEFAULT_SOURCE_URL_MODE', 'encoded'),
    'default_output_extension' => env('IMGPROXY_DEFAULT_OUTPUT_EXTENSION', 'jpeg'),
];
```

## Sample Test Responses

### Basic Test Response
```json
{
    "original": "https://picsum.photos/800/600",
    "processed": "http://localhost:8080/xyeeqF4mNUiHYF5afTDuCmDUuI0VcfCBbkEX3ig3-bo/width:400/height:300/aHR0cHM6Ly9waWNzdW0ucGhvdG9zLzgwMC82MDA.jpg",
    "test": "basic_url_generation"
}
```

### Performance Test Response
```json
{
    "urls_generated": 100,
    "duration_seconds": 0.0089,
    "urls_per_second": 1123.6,
    "sample_urls": ["url1", "url2", "url3"],
    "test": "performance"
}
```

## Development Notes

- **Routes**: Defined in `WorkbenchServiceProvider.php`
- **Views**: Located in `workbench/resources/views/`
- **Tests**: Integration tests in `tests/WorkbenchIntegrationTest.php`
- **Sample Images**: Uses Picsum Photos service for consistent test images
- **Performance**: Benchmarks URL generation speed (should be >100 URLs/second)

This workbench environment ensures the ImgProxy package works correctly in a real Laravel application context.