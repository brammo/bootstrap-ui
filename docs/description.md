# DescriptionHelper

Generate HTML description lists (`<dl>`) for displaying key-value pairs.

## Basic Usage

```php
echo $this->Description
    ->add('Name', 'John Doe')
    ->add('Email', 'john@example.com')
    ->add('Phone', '+1 234 567 890')
    ->render();
```

## Templates

The DescriptionHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `list` | `<dl{{attrs}}>{{content}}</dl>` |
| `term` | `<dt{{attrs}}>{{content}}</dt>` |
| `definition` | `<dd{{attrs}}>{{content}}</dd>` |

## Options

```php
echo $this->Description
    ->add('Label', 'Value')
    ->render([
        'list' => ['class' => 'row'],
    ]);
```

## Complete Example

```php
// Display user details
echo $this->Description
    ->add(__('Username'), h($user->username))
    ->add(__('Email'), h($user->email))
    ->add(__('Role'), h($user->role->name))
    ->add(__('Created'), $user->created->nice())
    ->add(__('Modified'), $user->modified->nice())
    ->render([
        'list' => ['class' => 'dl-horizontal'],
    ]);
```

See also: [Template customization](template-customization.md)
