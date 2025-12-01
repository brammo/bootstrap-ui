
# Bootstrap helpers

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A [CakePHP](https://cakephp.org/) plugin that extends [FriendsOfCake/bootstrap-ui](https://github.com/FriendsOfCake/bootstrap-ui) with additional Bootstrap 5 view helpers for building responsive UI components.

## Requirements

- PHP 8.1+
- CakePHP 5.0+
- [FriendsOfCake/bootstrap-ui](https://github.com/FriendsOfCake/bootstrap-ui) 5.0+

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
}
```

---

# View Helpers

The plugin provides several view helpers. All helpers use CakePHP's `StringTemplateTrait` for flexible template customization.

## Table of Contents

- [CardHelper](#cardhelper)
- [TableHelper](#tablehelper)
- [DescriptionHelper](#descriptionhelper)
- [NavHelper](#navhelper)
- [Template Customization](#template-customization)

---

## CardHelper

Create Bootstrap card components with optional header and footer sections.

### Basic Usage

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

### Templates

The CardHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `card` | `<div{{attrs}}>{{content}}</div>` |
| `header` | `<div{{attrs}}>{{content}}</div>` |
| `body` | `<div{{attrs}}>{{content}}</div>` |
| `footer` | `<div{{attrs}}>{{content}}</div>` |

### Default Classes

| Element | Default Class |
|---------|---------------|
| card | `card` |
| header | `card-header` |
| body | `card-body` |
| footer | `card-footer` |

### Options

| Option | Type | Description |
|--------|------|-------------|
| `header` | string\|null | Header content |
| `footer` | string\|null | Footer content |
| `headerAttrs` | array | HTML attributes for the header element |
| `bodyAttrs` | array | HTML attributes for the body element |
| `footerAttrs` | array | HTML attributes for the footer element |
| Other options | mixed | Applied as HTML attributes to the card element |

### Examples

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

---

## TableHelper

Build responsive HTML tables with headers and data rows.

### Basic Usage

```php
// Define header
$this->Table->header(['ID', 'Name', 'Email', 'Actions']);

// Add rows
$this->Table->row([1, 'John Doe', 'john@example.com', $actions]);
$this->Table->row([2, 'Jane Smith', 'jane@example.com', $actions]);

// Render table
echo $this->Table->render();
```

### Templates

The TableHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `wrapper` | `<div{{attrs}}>{{content}}</div>` |
| `table` | `<table{{attrs}}>{{content}}</table>` |
| `header` | `<thead{{attrs}}>{{content}}</thead>` |
| `body` | `<tbody{{attrs}}>{{content}}</tbody>` |
| `row` | `<tr{{attrs}}>{{content}}</tr>` |
| `headerCell` | `<th{{attrs}}>{{content}}</th>` |
| `bodyCell` | `<td{{attrs}}>{{content}}</td>` |

### Default Classes

| Element | Default Class |
|---------|---------------|
| wrapper | `table-responsive` |
| table | `table` |

### Header with Attributes

You can specify attributes for individual header cells:

```php
// Using array syntax for cell attributes
$this->Table->header([
    'ID',
    ['Name' => ['class' => 'w-25']],
    ['Email' => ['class' => 'w-50']],
    ['Actions' => ['class' => 'text-end']],
]);

// Alternative syntax
$this->Table->header([
    'ID',
    ['Name', ['class' => 'w-25']],
    ['Email', ['class' => 'w-50']],
    ['Actions', ['class' => 'text-end']],
]);
```

### Row Cells with Attributes

```php
$this->Table->row([
    $user->id,
    $user->name,
    $user->email,
    [$actions, ['class' => 'text-end']],
]);
```

### Row Options

You can specify HTML attributes for individual rows:

```php
// Add row with attributes
$this->Table->row([1, 'John Doe', 'john@example.com'], ['id' => 'row-1', 'class' => 'highlight']);
$this->Table->row([2, 'Jane Smith', 'jane@example.com'], ['data-id' => '2']);
```

### Body Options

You can set attributes on the `<tbody>` element using the `body()` method or via render options:

```php
// Using body() method
$this->Table->body(['id' => 'sortable-items', 'class' => 'sortable']);
$this->Table->row([1, 'Item 1']);
$this->Table->row([2, 'Item 2']);
echo $this->Table->render();

// Or via render options
$this->Table->row([1, 'Item 1']);
$this->Table->row([2, 'Item 2']);
echo $this->Table->render([
    'body' => ['id' => 'sortable-items'],
]);
```

### Render Options

```php
echo $this->Table->render([
    'wrapper' => ['class' => 'table-responsive-lg'],
    'table' => ['class' => 'table table-striped table-hover'],
    'body' => ['id' => 'table-body', 'data-controller' => 'sortable'],
]);
```

### Complete Example

```php
// Setup header
$this->Table->header([
    '#',
    'Name',
    'Email',
    'Status',
    ['Actions' => ['class' => 'text-end']],
]);

// Set body options (e.g., for sortable functionality)
$this->Table->body(['id' => 'sortable-users']);

// Add data rows with individual row options
foreach ($users as $user) {
    $status = $user->active 
        ? $this->Html->badge('Active', ['class' => 'bg-success'])
        : $this->Html->badge('Inactive', ['class' => 'bg-secondary']);
    
    $actions = $this->Html->link(['action' => 'edit', $user->id], ['class' => 'btn-primary btn-sm']) . ' ' .
               $this->Form->postLink(['action' => 'delete', $user->id], ['class' => 'btn-danger btn-sm']);
    
    $this->Table->row([
        $user->id,
        $user->name,
        $user->email,
        $status,
        [$actions, ['class' => 'text-end']],
    ], ['data-id' => $user->id]); // Row options
}

// Render with custom table classes
echo $this->Table->render([
    'table' => ['class' => 'table table-striped table-hover'],
]);
```

---

## DescriptionHelper

Generate HTML description lists (`<dl>`) for displaying key-value pairs.

### Basic Usage

```php
echo $this->Description
    ->add('Name', 'John Doe')
    ->add('Email', 'john@example.com')
    ->add('Phone', '+1 234 567 890')
    ->render();
```

### Templates

The DescriptionHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `list` | `<dl{{attrs}}>{{content}}</dl>` |
| `term` | `<dt{{attrs}}>{{content}}</dt>` |
| `definition` | `<dd{{attrs}}>{{content}}</dd>` |

### Options

```php
echo $this->Description
    ->add('Label', 'Value')
    ->render([
        'list' => ['class' => 'row'],
    ]);
```

### Complete Example

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

---

## NavHelper

Render Bootstrap 5 nav tabs or pills with built-in JavaScript tab-switching behavior. Supports both in-page tab panels (buttons) and navigational links.

### Basic Usage

```php
// Simple tabs with content panels
echo $this->Nav
    ->add('home', 'Home', '<p>Home content goes here...</p>')
    ->add('profile', 'Profile', '<p>Profile content goes here...</p>')
    ->add('settings', 'Settings', '<p>Settings content goes here...</p>')
    ->render();
```

### Pills Style

```php
echo $this->Nav
    ->add('tab1', 'Tab 1', 'Content 1')
    ->add('tab2', 'Tab 2', 'Content 2')
    ->render(['type' => 'pills']);
```

### Tabs with Icons

```php
echo $this->Nav
    ->add('home', 'Home', 'Home content', ['icon' => 'house'])
    ->add('profile', 'Profile', 'Profile content', ['icon' => 'person'])
    ->add('settings', 'Settings', 'Settings content', ['icon' => 'gear'])
    ->render();
```

### Navigational Links

For navigation between pages (no tab panels):

```php
echo $this->Nav
    ->addLink('Dashboard', ['controller' => 'Dashboard', 'action' => 'index'], ['active' => true])
    ->addLink('Users', ['controller' => 'Users', 'action' => 'index'])
    ->addLink('Settings', ['controller' => 'Settings', 'action' => 'index'], ['icon' => 'gear'])
    ->renderNav(['type' => 'pills']);
```

### Mixed Tabs and Links

```php
echo $this->Nav
    ->add('details', 'Details', $detailsContent)
    ->add('history', 'History', $historyContent)
    ->addLink('View All', ['action' => 'index'], ['icon' => 'list'])
    ->render();
```

### Methods

| Method | Description |
|--------|-------------|
| `add($id, $title, $content, $options)` | Add a tab with panel content (renders as button) |
| `addLink($title, $url, $options)` | Add a navigational link (no panel) |
| `render($options)` | Render nav with tab content panels |
| `renderNav($options)` | Render nav only (no panels) |

### Tab/Link Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `icon` | string | `null` | Bootstrap Icons name (e.g., `'house'`, `'gear'`) |
| `active` | bool | `false` | Force this tab/link to be active (first tab is active by default) |
| `disabled` | bool | `false` | Disable the tab/link |

### Render Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `type` | string | `'tabs'` | Nav type: `'tabs'` or `'pills'` |
| `fade` | bool | `true` | Enable fade animation on tab switch |
| `fill` | bool | `false` | Make nav items fill available width |
| `justified` | bool | `false` | Make nav items equal width |
| `vertical` | bool | `false` | Render nav vertically with flex layout |
| `navAttrs` | array | `[]` | HTML attributes for the nav element |
| `contentAttrs` | array | `[]` | HTML attributes for the tab-content wrapper |

### Templates

The NavHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `nav` | `<ul{{attrs}}>{{content}}</ul>` |
| `navItem` | `<li{{attrs}}>{{content}}</li>` |
| `navButton` | `<button{{attrs}}>{{content}}</button>` |
| `navLink` | `<a{{attrs}}>{{content}}</a>` |
| `tabContent` | `<div{{attrs}}>{{content}}</div>` |
| `tabPane` | `<div{{attrs}}>{{content}}</div>` |

### Default Classes

| Element | Default Classes |
|---------|-----------------|
| nav | `nav nav-tabs` or `nav nav-pills` |
| navItem | `nav-item` |
| navButton | `nav-link` |
| navLink | `nav-link` |
| tabContent | `tab-content` |
| tabPane | `tab-pane fade` |

### Accessibility

The helper automatically includes proper ARIA attributes:

- Nav: `role="tablist"`
- Nav item: `role="presentation"`
- Button: `role="tab"`, `aria-controls`, `aria-selected`
- Tab pane: `role="tabpanel"`, `tabindex="0"`, `aria-labelledby`
- Disabled: `aria-disabled="true"`, `tabindex="-1"`
- Active link: `aria-current="page"`

### Bootstrap 5 Integration

Tab switching is handled automatically by Bootstrap 5's JavaScript via data attributes:

- `data-bs-toggle="tab"` on buttons
- `data-bs-target="#panel-id"` pointing to the tab pane

No additional JavaScript is required.

### Complete Examples

#### User Profile Tabs

```php
// Define tab content
$personalInfo = $this->element('user/personal_info', ['user' => $user]);
$security = $this->element('user/security', ['user' => $user]);
$preferences = $this->element('user/preferences', ['user' => $user]);

// Render tabs
echo $this->Nav
    ->add('personal', 'Personal Info', $personalInfo, ['icon' => 'person'])
    ->add('security', 'Security', $security, ['icon' => 'shield-lock'])
    ->add('preferences', 'Preferences', $preferences, ['icon' => 'sliders'])
    ->render(['type' => 'pills', 'fill' => true]);
```

#### Vertical Tabs

```php
echo $this->Nav
    ->add('overview', 'Overview', $overviewContent)
    ->add('analytics', 'Analytics', $analyticsContent)
    ->add('reports', 'Reports', $reportsContent)
    ->add('settings', 'Settings', $settingsContent, ['icon' => 'gear'])
    ->render(['type' => 'pills', 'vertical' => true]);
```

#### Navigation Menu

```php
echo $this->Nav
    ->addLink('All Users', ['action' => 'index'], ['active' => $this->request->getParam('action') === 'index'])
    ->addLink('Active', ['action' => 'index', '?' => ['status' => 'active']])
    ->addLink('Inactive', ['action' => 'index', '?' => ['status' => 'inactive']])
    ->addLink('Add New', ['action' => 'add'], ['icon' => 'plus'])
    ->renderNav(['type' => 'pills']);
```

---

## Template Customization

All helpers use CakePHP's `StringTemplateTrait`, allowing you to customize templates at runtime or through configuration.

### Runtime Customization

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

### Configuration-based Customization

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

### Template Placeholders

| Placeholder | Description |
|-------------|-------------|
| `{{attrs}}` | HTML attributes formatted as string |
| `{{content}}` | Inner content |

---

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

---

## License

This plugin is licensed under the [MIT License](LICENSE).

## Author

Roman Sidorkin - [roman.sidorkin@gmail.com](mailto:roman.sidorkin@gmail.com)
