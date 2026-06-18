# NavHelper

Render Bootstrap 5 nav tabs or pills with built-in JavaScript tab-switching behavior. Supports both in-page tab panels (buttons) and navigational links. Requires FriendsOfCake `BootstrapUI.Html` (see [Usage](../README.md#usage) in the README).

## Basic Usage

```php
// Simple tabs with content panels
echo $this->Nav
    ->add('home', 'Home', '<p>Home content goes here...</p>')
    ->add('profile', 'Profile', '<p>Profile content goes here...</p>')
    ->add('settings', 'Settings', '<p>Settings content goes here...</p>')
    ->render();
```

## Pills Style

```php
echo $this->Nav
    ->add('tab1', 'Tab 1', 'Content 1')
    ->add('tab2', 'Tab 2', 'Content 2')
    ->render(['type' => 'pills']);
```

## Active tab

The first tab is active by default. Set `'active' => true` on one tab to open a different panel; only that tab and its pane receive the active state.

```php
echo $this->Nav
    ->add('overview', 'Overview', $overviewContent)
    ->add('details', 'Details', $detailsContent, ['active' => true])
    ->render();
```

## Tabs with Icons

```php
echo $this->Nav
    ->add('home', 'Home', 'Home content', ['icon' => 'house'])
    ->add('profile', 'Profile', 'Profile content', ['icon' => 'person'])
    ->add('settings', 'Settings', 'Settings content', ['icon' => 'gear'])
    ->render();
```

## Navigational Links

For navigation between pages (no tab panels):

```php
echo $this->Nav
    ->addLink('Dashboard', ['controller' => 'Dashboard', 'action' => 'index'], ['active' => true])
    ->addLink('Users', ['controller' => 'Users', 'action' => 'index'])
    ->addLink('Settings', ['controller' => 'Settings', 'action' => 'index'], ['icon' => 'gear'])
    ->renderNav(['type' => 'pills']);
```

## Mixed Tabs and Links

```php
echo $this->Nav
    ->add('details', 'Details', $detailsContent)
    ->add('history', 'History', $historyContent)
    ->addLink('View All', ['action' => 'index'], ['icon' => 'list'])
    ->render();
```

## Methods

| Method | Description |
|--------|-------------|
| `add($id, $title, $content, $options)` | Add a tab with panel content (renders as button) |
| `addLink($title, $url, $options)` | Add a navigational link (no panel) |
| `render($options)` | Render nav with tab content panels |
| `renderNav($options)` | Render nav only (no panels) |

## Tab/Link Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `icon` | string | `null` | Bootstrap Icons name (e.g., `'house'`, `'gear'`) |
| `active` | bool | `false` | Mark tab/link as active (`add`: first tab is active if none set; only one tab panel is active) |
| `disabled` | bool | `false` | Disable the tab/link |
| Other options | mixed | — | HTML attributes for the tab button (`add`) or link (`addLink`) |

## Render Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `type` | string | `'tabs'` | Nav type: `'tabs'` or `'pills'` |
| `fade` | bool | `true` | Enable fade animation on tab switch |
| `fill` | bool | `false` | Make nav items fill available width |
| `justified` | bool | `false` | Make nav items equal width |
| `vertical` | bool | `false` | Render nav vertically with flex layout |
| `navAttrs` | array | `[]` | HTML attributes for the nav element |
| `contentAttrs` | array | `[]` | HTML attributes for the tab-content wrapper |

## Templates

The NavHelper uses the following default templates:

| Template | Default HTML |
|----------|--------------|
| `nav` | `<ul{{attrs}}>{{content}}</ul>` |
| `navItem` | `<li{{attrs}}>{{content}}</li>` |
| `navButton` | `<button{{attrs}}>{{content}}</button>` |
| `navLink` | `<a{{attrs}}>{{content}}</a>` |
| `tabContent` | `<div{{attrs}}>{{content}}</div>` |
| `tabPane` | `<div{{attrs}}>{{content}}</div>` |

## Default Classes

| Element | Default Classes |
|---------|-----------------|
| nav | `nav nav-tabs` or `nav nav-pills` |
| navItem | `nav-item` |
| navButton | `nav-link` |
| navLink | `nav-link` |
| tabContent | `tab-content` |
| tabPane | `tab-pane fade` |

## Accessibility

The helper automatically includes proper ARIA attributes:

- Nav: `role="tablist"`
- Nav item: `role="presentation"` (tab buttons only; omitted for `addLink` items)
- Tab button: `id="{tab-id}-tab"`, `role="tab"`, `aria-controls`, `aria-selected`
- Tab pane: `id="{tab-id}"`, `role="tabpanel"`, `tabindex="0"`, `aria-labelledby="{tab-id}-tab"`
- Disabled: `aria-disabled="true"`, `tabindex="-1"`
- Active link: `aria-current="page"`

## Bootstrap 5 Integration

Tab switching is handled automatically by Bootstrap 5's JavaScript via data attributes:

- `data-bs-toggle="tab"` on buttons
- `data-bs-target="#panel-id"` pointing to the tab pane

No additional JavaScript is required.

## Complete Examples

### User Profile Tabs

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

### Vertical Tabs

With tab panels, vertical layout wraps the nav and content in `<div class="d-flex align-items-start">` and adds `flex-column` to the nav.

```php
echo $this->Nav
    ->add('overview', 'Overview', $overviewContent)
    ->add('analytics', 'Analytics', $analyticsContent)
    ->add('reports', 'Reports', $reportsContent)
    ->add('settings', 'Settings', $settingsContent, ['icon' => 'gear'])
    ->render(['type' => 'pills', 'vertical' => true]);
```

### Navigation Menu

```php
echo $this->Nav
    ->addLink('All Users', ['action' => 'index'], ['active' => $this->request->getParam('action') === 'index'])
    ->addLink('Active', ['action' => 'index', '?' => ['status' => 'active']])
    ->addLink('Inactive', ['action' => 'index', '?' => ['status' => 'inactive']])
    ->addLink('Add New', ['action' => 'add'], ['icon' => 'plus'])
    ->renderNav(['type' => 'pills']);
```

See also: [Template customization](template-customization.md)
