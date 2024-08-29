# Changelog

All notable changes to `laravel-imgproxy` will be documented in this file.

## v0.1.0 - Basic URL Generation

Dear developers,

We are pleased to announce the release of version 0.1.0 of our Laravel ImgProxy package. This initial release focuses on establishing core functionality for URL generation. Here's a detailed overview of the features and changes:

### Features
- Implemented basic URL generation, allowing for easy creation of ImgProxy URLs
- Set up a configuration file for customizable settings
- Created the ImgProxy class, serving as the core of the package
- Developed a Facade for convenient package usage
- Introduced the `imgproxy()` helper function for those who prefer helper methods
- Implemented signed URL generation for enhanced security
- Added methods to set width and height, enabling straightforward image resizing
- Implemented various resize types, providing flexibility in image manipulation

### Changes
- Updated the configuration file to include key and salt parameters for signed URL functionality
- Enhanced the ImgProxy class to support URL signing
- Expanded URL generation to incorporate resize parameters
