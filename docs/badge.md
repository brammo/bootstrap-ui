# BadgeHelper

Create Bootstrap 5 badges, including color shortcuts, inline button badges, and positioned notification badges.

## Basic Usage

```php
// Secondary badge
echo $this->Badge->render('New');

// Theme colors
echo $this->Badge->primary('New');
echo $this->Badge->success('Active', ['pill' => true]);
echo $this->Badge->danger('3', ['class' => 'ms-1']);

// Custom background without the default color
echo $this->Badge->render('Custom', [
    'class' => 'bg-body-secondary text-primary ms-1',
]);
```

## Templates

The BadgeHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `badge` | `<{{tag}}{{attrs}}>{{content}}</{{tag}}>` |
| `button` | `<button{{attrs}}>{{content}}</button>` |
| `visuallyHidden` | `<span{{attrs}}>{{content}}</span>` |

## Default Classes

| Element | Default Class |
|---------|---------------|
| badge | `badge` plus `text-bg-secondary` when neither `color` nor `class` is set |
| button | `btn btn-primary` |
| visually hidden | `visually-hidden` |

`positioned` badges use `position-absolute top-0 start-100 translate-middle` and `bg-{color}` instead of `text-bg-{color}`. Indicator dots use `rounded-circle` and do not include the `badge` class.

## Options

`render()` options:

| Option | Type | Description |
|--------|------|-------------|
| `color` | string\|null | Theme name. Adds `text-bg-{color}`, or `bg-{color}` when `positioned` or `indicator` is set. Defaults to `secondary` only when `class` is also omitted. |
| `class` | string\|null | Custom color or background classes, or extra utilities such as padding and margin. Appended when `color` is set. When `color` is omitted, no default color class is added. |
| `pill` | bool | Adds `rounded-pill`. Default `false`. Default `true` for positioned badges that are not indicator dots. |
| `tag` | string | HTML tag. Default `span`. |
| `positioned` | bool | Corner count badge. |
| `indicator` | bool | Notification dot. Visible badge text is omitted. Takes precedence over `positioned`. |
| `visuallyHidden` | string\|null | Screen-reader text inside the badge. |
| Other options | mixed | Applied as HTML attributes to the badge element. |

Content is not escaped.

Color wrappers call `render()` with `color` set: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`. Other options, including `class`, are passed through.

`button()` options:

| Option | Type | Description |
|--------|------|-------------|
| `buttonAttrs` | array | HTML attributes for the `<button>`. Default `type` is `button` and default `class` is `btn btn-primary`. A provided `class` replaces `btn btn-primary`. |
| `badgeAttrs` | array | Extra HTML attributes for the inner badge. Top-level badge options win when both set the same key. |
| Other options | mixed | Badge options passed to `render()`, including `color`, `class`, `pill`, `tag`, `visuallyHidden`, `positioned`, and `indicator`. |

`positioned` or `indicator` adds `position-relative` to the button.

Passing `class` on a badge **appends** to the structural classes (`badge`, pill, position utilities). The theme color class is added only when `color` is set, or when both `color` and `class` are omitted.

## Examples

```php
// Pill badge
echo $this->Badge->primary('New', ['pill' => true]);

// Positioned count on any relative parent you provide
echo $this->Badge->render('99+', [
    'color' => 'danger',
    'positioned' => true,
    'visuallyHidden' => 'unread messages',
]);

// Indicator dot
echo $this->Badge->render('', [
    'indicator' => true,
    'color' => 'danger',
    'visuallyHidden' => 'New alerts',
]);

// Inline badge in a button
echo $this->Badge->button('Notifications', '4');

// Positioned count on a button
echo $this->Badge->button('Inbox', '99+', [
    'positioned' => true,
    'color' => 'danger',
    'visuallyHidden' => 'unread messages',
]);

// Indicator dot on a button
echo $this->Badge->button('Profile', '', [
    'indicator' => true,
    'color' => 'danger',
    'visuallyHidden' => 'New alerts',
    'buttonAttrs' => ['class' => 'btn btn-primary'],
]);
```

See also: [Template customization](template-customization.md)
