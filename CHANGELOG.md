# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.0] - 2026-01-26

### Changed

- Renamed `Plugin` class to `BootstrapUIPlugin` to fix CakePHP 5.3.0 deprecation warning

## [1.1.0] - 2025-12-01

### Added

- `TableHelper`: Added `body()` method to set HTML attributes on `<tbody>` element
- `TableHelper`: Added `body` option to `render()` method for tbody attributes
- `TableHelper`: Added optional second parameter to `row()` method for row attributes

## [1.0.0] - 2025-11-30

### Added

- Initial release
- `CardHelper` for Bootstrap card components
- `TableHelper` for responsive HTML tables
- `DescriptionHelper` for description lists
- `NavHelper` for Bootstrap 5 nav tabs and pills
