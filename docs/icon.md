# IconHelper

Render icon-font markup from a configurable namespace and prefix. The default set is [Bootstrap Icons](https://icons.getbootstrap.com/). The same helper can render Font Awesome, Tabler Icons, or another set, including more than one set on the same page.

## Basic Usage

```php
echo $this->Icon->icon('house');
```

```html
<i class="bi bi-house"></i>
```

## Default icon set

Set `namespace` and `prefix` when loading the helper. `namespace` is the set class (`bi`, `fa-solid`, `fas`, `ti`). `prefix` is the class prefix; the icon class is `{prefix}-{name}`.

```php
$this->loadHelper('Brammo/BootstrapUI.Icon', [
    'namespace' => 'fa-solid',
    'prefix' => 'fa',
]);
```

```php
echo $this->Icon->icon('user');
```

```html
<i class="fa-solid fa-user"></i>
```

## Another set on the same page

`namespace` and `prefix` on one call override the helper defaults.

```php
// Font Awesome 6 solid
echo $this->Icon->icon('user', [
    'namespace' => 'fa-solid',
    'prefix' => 'fa',
]);

// Font Awesome brands
echo $this->Icon->icon('github', [
    'namespace' => 'fa-brands',
    'prefix' => 'fa',
]);

// Tabler Icons
echo $this->Icon->icon('user', [
    'namespace' => 'ti',
    'prefix' => 'ti',
]);
```

```html
<i class="fa-solid fa-user"></i>
<i class="fa-brands fa-github"></i>
<i class="ti ti-user"></i>
```

## Size

`size` adds `{prefix}-{size}` when `prefix` is not empty. Bootstrap Icons uses tokens such as `sm` and `lg`. Font Awesome uses tokens such as `lg` and `2xl`.

```php
echo $this->Icon->icon('house', ['size' => 'lg']);
```

```html
<i class="bi bi-house bi-lg"></i>
```

## Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `namespace` | string | `'bi'` | Set class. An empty string omits it. |
| `prefix` | string | `'bi'` | Class prefix. The icon class is `{prefix}-{name}`. An empty string uses the icon name as the class. |
| `size` | string\|null | `null` | Size token. Adds `{prefix}-{size}` when `prefix` is not empty. |
| `tag` | string | `'i'` | HTML tag. |
| `class` | string\|null | `null` | Extra classes appended to the generated icon classes. |
| Other options | mixed | — | HTML attributes on the icon element. |

## Templates

| Template | Default HTML |
|----------|--------------|
| `icon` | `<{{tag}}{{attrs}}></{{tag}}>` |

See also: [Template customization](template-customization.md)
