# Changelog

All notable changes to `laravel-imgproxy` will be documented in this file.

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
