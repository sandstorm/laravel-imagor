# Changelog

All notable changes to `laravel-imgproxy` will be documented in this file.

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
