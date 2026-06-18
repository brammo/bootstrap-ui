# CardHelper

Create Bootstrap card components with optional header and footer sections.

## Basic Usage

```php
// Simple card with body content
echo $this->Card->render('This is the card body content.');

// Card with header
echo $this->Card->render('Card body content', [
    'header' => 'Card Title',
]);

// Card with header and footer
echo $this->Card->render('Card body content', [
    'header' => 'Card Title',
    'footer' => 'Card footer text',
]);
```

## Templates

The CardHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `card` | `<div{{attrs}}>{{content}}</div>` |
| `header` | `<div{{attrs}}>{{content}}</div>` |
| `body` | `<div{{attrs}}>{{content}}</div>` |
| `footer` | `<div{{attrs}}>{{content}}</div>` |

## Default Classes

| Element | Default Class |
|---------|---------------|
| card | `card` |
| header | `card-header` |
| body | `card-body` |
| footer | `card-footer` |

## Options

| Option | Type | Description |
|--------|------|-------------|
| `header` | string\|null | Header content |
| `footer` | string\|null | Footer content |
| `headerAttrs` | array | HTML attributes for the header element |
| `bodyAttrs` | array | HTML attributes for the body element |
| `footerAttrs` | array | HTML attributes for the footer element |
| Other options | mixed | Applied as HTML attributes to the card element |

Passing `class` (or other keys) on the card or via `*Attrs` **replaces** the default attribute for that element; include Bootstrap classes in your value when you need them (for example `'class' => 'card shadow-sm'`).

## Examples

```php
// Card with custom classes
echo $this->Card->render('Content', [
    'header' => 'Title',
    'class' => 'card shadow-sm',
]);

// Card with custom body padding
echo $this->Card->render('Content', [
    'bodyAttrs' => ['class' => 'card-body p-4'],
]);

// Card with styled header
echo $this->Card->render('Content', [
    'header' => 'Important Notice',
    'headerAttrs' => ['class' => 'card-header bg-warning text-dark'],
]);

// Card with HTML content
$header = $this->Html->tag('h5', 'User Details', ['class' => 'mb-0']);
$body = $this->element('user_info', ['user' => $user]);
echo $this->Card->render($body, [
    'header' => $header,
    'class' => 'card mb-4',
]);
```

See also: [Template customization](template-customization.md)
