# Bootstrap helpers

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A [CakePHP](https://cakephp.org/) plugin that extends [FriendsOfCake/bootstrap-ui](https://github.com/FriendsOfCake/bootstrap-ui) with additional Bootstrap 5 view helpers for building responsive UI components.

## Requirements

- PHP 8.1+
- CakePHP 5.3+
- [FriendsOfCake/bootstrap-ui](https://github.com/FriendsOfCake/bootstrap-ui) 5.1+ (for `NavHelper` icons and URL building via `BootstrapUI.Html`)

## Installation

You can install this plugin using [Composer](https://getcomposer.org):

```bash
composer require brammo/bootstrap-ui
```

### Load the Plugin

Add the following to your `Application.php`:

```php
public function bootstrap(): void
{
    parent::bootstrap();
    
    $this->addPlugin('Brammo/BootstrapUI');
}
```

Or load via command line:

```bash
bin/cake plugin load Brammo/BootstrapUI
```

## Usage

Load the helpers in your `AppView.php`:

```php
public function initialize(): void
{
    parent::initialize();
    
    // Load individual helpers
    $this->loadHelper('Brammo/BootstrapUI.Card');
    $this->loadHelper('Brammo/BootstrapUI.Table');
    $this->loadHelper('Brammo/BootstrapUI.Description');
    $this->loadHelper('Brammo/BootstrapUI.Nav');
    $this->loadHelper('Brammo/BootstrapUI.Carousel');
}
```

`NavHelper` uses FriendsOfCake’s `BootstrapUI.Html` helper (icons, array URLs). Load that helper in `AppView` if it is not already available from your bootstrap-ui setup.

## View Helpers

The plugin provides several view helpers. All helpers use CakePHP's `StringTemplateTrait` for flexible template customization.

| Helper | Description | Documentation |
|--------|-------------|---------------|
| `CardHelper` | Bootstrap cards with optional header and footer | [docs/card.md](docs/card.md) |
| `TableHelper` | Responsive HTML tables with headers and rows | [docs/table.md](docs/table.md) |
| `DescriptionHelper` | Description lists (`<dl>`) for key-value pairs | [docs/description.md](docs/description.md) |
| `NavHelper` | Nav tabs/pills with tab panels or links | [docs/nav.md](docs/nav.md) |
| `CarouselHelper` | Carousels with controls, indicators, and captions | [docs/carousel.md](docs/carousel.md) |

See [docs/template-customization.md](docs/template-customization.md) for customizing helper templates at runtime or via configuration.

## Tests

Run the test suite with PHPUnit:

```bash
composer test
```

### Code Quality

Run code style checks:

```bash
composer cs-check
```

Fix code style issues:

```bash
composer cs-fix
```

### Static Analysis

Run PHPStan and Psalm:

```bash
composer analyse
```

Or run them individually:

```bash
composer stan
composer psalm
```

## License

This plugin is licensed under the [MIT License](LICENSE).

## Author

Roman Sidorkin - [roman.sidorkin@gmail.com](mailto:roman.sidorkin@gmail.com)
