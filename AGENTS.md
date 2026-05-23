# Agent guide — brammo/bootstrap-ui

CakePHP 5 plugin that adds Bootstrap 5 view helpers on top of [FriendsOfCake/bootstrap-ui](https://github.com/FriendsOfCake/bootstrap-ui).

## Layout

| Path | Purpose |
|------|---------|
| `src/View/Helper/` | Card, Table, Description, Nav helpers |
| `src/BootstrapUIPlugin.php` | Plugin class (no auto-registration of helpers) |
| `tests/TestCase/View/Helper/` | PHPUnit tests (1:1 with helpers) |
| `README.md` | Public API and usage examples |

## Commands

```bash
composer test      # PHPUnit
composer cs-check  # PHPCS (src + tests)
composer check     # test + cs-check
composer analyse   # PHPStan (level 8, src/) + Psalm
```

Run `composer check` after helper or test changes. Run `composer analyse` before larger refactors.

## Plugin vs helpers

`BootstrapUIPlugin` does **not** register helpers. Host apps must:

1. `$this->addPlugin('Brammo/BootstrapUI')` in `Application.php`
2. `$this->loadHelper('Brammo/BootstrapUI.Card')` (and Table, Description, Nav) in `AppView::initialize()`

`NavHelper` depends on FoC `BootstrapUI.Html` for icons and URL building.

## Conventions

- PHP 8.1+, `declare(strict_types=1);`, namespace `Brammo\BootstrapUI\...`
- Helpers use `StringTemplateTrait`, configurable templates, and `mergeAttributes()` for defaults
- Fluent helpers (Table, Description, Nav) reset internal state in `render()`
- **Every behavior change needs a matching test** in `tests/TestCase/View/Helper/`
- Assert Bootstrap markup (classes, ARIA, `data-bs-*`), not only content substrings
- Do not commit unless the user asks
- Update `README.md` when public options or behavior change

## Cursor rules

See `.cursor/rules/` for file-specific guidance (`bootstrap-ui.mdc`, `view-helpers.mdc`, `helper-tests.mdc`).
