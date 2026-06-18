# TableHelper

Build responsive HTML tables with headers and data rows. Calling `render()` clears the header, rows, and body options so you can build another table on the same helper instance.

## Basic Usage

```php
// Define header
$this->Table->header(['ID', 'Name', 'Email', 'Actions']);

// Add rows
$this->Table->row([1, 'John Doe', 'john@example.com', $actions]);
$this->Table->row([2, 'Jane Smith', 'jane@example.com', $actions]);

// Render table
echo $this->Table->render();
```

## Templates

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

## Default Classes

| Element | Default Class |
|---------|---------------|
| wrapper | `table-responsive` |
| table | `table` |

## Header (`<thead>`) attributes

Pass a second argument to `header()` for attributes on the `<thead>` element:

```php
$this->Table->header(
    ['ID', 'Name', 'Email'],
    ['class' => 'table-light', 'id' => 'users-head'],
);
```

## Header cell attributes

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

## Row Cells with Attributes

```php
$this->Table->row([
    $user->id,
    $user->name,
    $user->email,
    [$actions, ['class' => 'text-end']],
]);
```

## Row Options

You can specify HTML attributes for individual rows:

```php
// Add row with attributes
$this->Table->row([1, 'John Doe', 'john@example.com'], ['id' => 'row-1', 'class' => 'highlight']);
$this->Table->row([2, 'Jane Smith', 'jane@example.com'], ['data-id' => '2']);
```

## Body Options

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

## Render Options

```php
echo $this->Table->render([
    'wrapper' => ['class' => 'table-responsive-lg'],
    'table' => ['class' => 'table table-striped table-hover'],
    'body' => ['id' => 'table-body', 'data-controller' => 'sortable'],
]);
```

## Complete Example

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

See also: [Template customization](template-customization.md)
