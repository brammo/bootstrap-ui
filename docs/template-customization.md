# Template Customization

All helpers use CakePHP's `StringTemplateTrait`, allowing you to customize templates at runtime or through configuration.

## Runtime Customization

```php
// Customize CardHelper templates
$this->Card->setTemplates([
    'card' => '<article{{attrs}}>{{content}}</article>',
    'header' => '<header{{attrs}}>{{content}}</header>',
    'body' => '<section{{attrs}}>{{content}}</section>',
    'footer' => '<footer{{attrs}}>{{content}}</footer>',
]);

// Customize TableHelper templates
$this->Table->setTemplates([
    'wrapper' => '<div{{attrs}}>{{content}}</div>',
    'table' => '<table{{attrs}}>{{content}}</table>',
]);
```

## Configuration-based Customization

In your `AppView.php`:

```php
public function initialize(): void
{
    parent::initialize();
    
    $this->loadHelper('Brammo/BootstrapUI.Card', [
        'templates' => [
            'card' => '<div{{attrs}}>{{content}}</div>',
        ],
    ]);
}
```

## Template Placeholders

| Placeholder | Description |
|-------------|-------------|
| `{{attrs}}` | HTML attributes formatted as string |
| `{{content}}` | Inner content |

## Default attributes

Helpers merge `$_defaultAttributes` with your options (`$yourAttrs + $defaults`). Your keys win; `class` is not merged automatically—set the full class string when overriding defaults.
