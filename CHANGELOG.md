# Changelog

All notable changes to `laravel-imgproxy` will be documented in this file.

## v0.4.0 - 2025-07-18

### 🚀 Major Features

- **Visual Effects Engine**: New methods for blur, sharpen, brightness, contrast, and saturation adjustments
- **Quality Control**: Fine-grained compression control (0-100) for optimal file sizes
- **Enhanced Validation**: Comprehensive parameter bounds checking with clear error messages
- **Fluent API Extensions**: All new methods support method chaining for clean, readable code

### 🎨 New Methods

- `setQuality(int $quality)` - Control compression quality (0-100)
- `setBlur(float $sigma)` - Apply blur effects
- `setSharpen(float $sigma)` - Enhance image sharpness
- `setBrightness(int $brightness)` - Adjust brightness (-255 to 255)
- `setContrast(float $contrast)` - Modify contrast levels
- `setSaturation(float $saturation)` - Control color saturation

### 🧪 Testing & Development

- **39 Comprehensive Tests**: 26 unit + 13 integration + 7 architecture tests
- **Interactive Workbench**: Visual testing environment with real image processing
- **Performance Validated**: >1000 URLs/second generation speed
- **Visual Test Suite**: Browser-based validation with sample images

### 📚 Documentation Overhaul

- Complete API reference with parameter details and examples
- Laravel integration patterns (Blade directives, Eloquent accessors, API resources)
- Performance optimization strategies and responsive image examples
- Security best practices and comprehensive troubleshooting guide

### 🔧 Developer Experience

- Interactive testing endpoints for visual validation
- Enhanced error messages with specific validation details
- Development configuration with testing commands
- Real-time visual effects preview in workbench

### 📈 Technical Improvements

- Type-safe parameter validation for all new methods
- Backward compatibility maintained with existing APIs
- Enhanced fluent interface design
- Laravel 10+ compatibility preserved

### 🎯 Use Cases

Perfect for e-commerce product images, user avatars, photo galleries, responsive images, and any application requiring dynamic image processing with visual enhancement capabilities.

**Upgrade Note**: Fully backward compatible - existing code continues to work without changes.

## v0.3.0 - 2025-02-27

### v0.3.0

This update includes several behind-the-scenes improvements to enhance the stability and performance of the Laravel ImgProxy integration.  We've also corrected a minor spelling error.

#### What's Changed

* chore(deps): bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/imsus/laravel-imgproxy/pull/2
* chore(deps): bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/imsus/laravel-imgproxy/pull/3
* Typo by @felixdorn in https://github.com/imsus/laravel-imgproxy/pull/1

#### New Contributors

* @dependabot made their first contribution in https://github.com/imsus/laravel-imgproxy/pull/2
* @felixdorn made their first contribution in https://github.com/imsus/laravel-imgproxy/pull/1

**Full Changelog**: https://github.com/imsus/laravel-imgproxy/compare/v0.2.1...v0.3.0

## v0.2.1 - 2024-09-25

**Error Handling Enhancement**

- We've improved the error handling mechanism by adding a try-catch block.
- Now, if an InvalidArgumentException occurs during validateSourceUrl(), the system will fallback to the original source URL.

**Full Changelog**: https://github.com/imsus/laravel-imgproxy/compare/v0.2.0...v0.2.1

## v0.2.0 - 2024-09-25

**Device Pixel Ratio Adjustment**

- Introduced `setDpr` method to customize device pixel ratio
- Enables developers to optimize image quality for various screen resolutions

**Full Changelog**: https://github.com/imsus/laravel-imgproxy/compare/v0.1.1...v0.2.0
