<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\Test\TestCase\View\Helper;

use Brammo\BootstrapUI\View\Helper\TableHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * BootstrapUI\View\Helper\TableHelper Test Case
 */
class TableHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BootstrapUI\View\Helper\TableHelper
     */
    protected TableHelper $Table;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Table = new TableHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Table);
        parent::tearDown();
    }

    /**
     * Test render method with empty table
     *
     * @return void
     */
    public function testRenderEmptyTable(): void
    {
        $result = $this->Table->render();

        $this->assertStringContainsString('<table', $result);
        $this->assertStringContainsString('class="table"', $result);
        $this->assertStringContainsString('table-responsive', $result);
    }

    /**
     * Test header method
     *
     * @return void
     */
    public function testHeader(): void
    {
        $this->Table->header(['ID', 'Name', 'Email']);
        $result = $this->Table->render();

        $this->assertStringContainsString('<thead', $result);
        $this->assertStringContainsString('<th', $result);
        $this->assertStringContainsString('ID', $result);
        $this->assertStringContainsString('Name', $result);
        $this->assertStringContainsString('Email', $result);
    }

    /**
     * Test header with attributes
     *
     * @return void
     */
    public function testHeaderWithAttributes(): void
    {
        $this->Table->header([
            ['ID', ['class' => 'id-column']],
            ['Name', ['class' => 'name-column']],
        ]);
        $result = $this->Table->render();

        $this->assertStringContainsString('class="id-column"', $result);
        $this->assertStringContainsString('class="name-column"', $result);
    }

    /**
     * Test row method
     *
     * @return void
     */
    public function testRow(): void
    {
        $this->Table->row([1, 'John Doe', 'john@example.com']);
        $result = $this->Table->render();

        $this->assertStringContainsString('<tbody', $result);
        $this->assertStringContainsString('<tr', $result);
        $this->assertStringContainsString('<td', $result);
        $this->assertStringContainsString('1', $result);
        $this->assertStringContainsString('John Doe', $result);
        $this->assertStringContainsString('john@example.com', $result);
    }

    /**
     * Test row with cell attributes
     *
     * @return void
     */
    public function testRowWithCellAttributes(): void
    {
        $this->Table->row([
            [1, ['class' => 'id-cell']],
            ['John Doe', ['class' => 'name-cell']],
        ]);
        $result = $this->Table->render();

        $this->assertStringContainsString('class="id-cell"', $result);
        $this->assertStringContainsString('class="name-cell"', $result);
    }

    /**
     * Test multiple rows
     *
     * @return void
     */
    public function testMultipleRows(): void
    {
        $this->Table->row([1, 'John Doe', 'john@example.com']);
        $this->Table->row([2, 'Jane Smith', 'jane@example.com']);
        $result = $this->Table->render();

        $this->assertStringContainsString('John Doe', $result);
        $this->assertStringContainsString('Jane Smith', $result);
        $this->assertStringContainsString('john@example.com', $result);
        $this->assertStringContainsString('jane@example.com', $result);
    }

    /**
     * Test complete table with header and rows
     *
     * @return void
     */
    public function testCompleteTable(): void
    {
        $this->Table->header(['ID', 'Name', 'Email']);
        $this->Table->row([1, 'John Doe', 'john@example.com']);
        $this->Table->row([2, 'Jane Smith', 'jane@example.com']);
        $result = $this->Table->render();

        $this->assertStringContainsString('<thead', $result);
        $this->assertStringContainsString('<tbody', $result);
        $this->assertStringContainsString('ID', $result);
        $this->assertStringContainsString('Name', $result);
        $this->assertStringContainsString('John Doe', $result);
        $this->assertStringContainsString('Jane Smith', $result);
    }

    /**
     * Test render with custom options
     *
     * @return void
     */
    public function testRenderWithCustomOptions(): void
    {
        $this->Table->row([1, 'Test']);
        $result = $this->Table->render([
            'table' => ['class' => 'table table-striped custom-class'],
        ]);

        $this->assertStringContainsString('table-striped', $result);
        $this->assertStringContainsString('custom-class', $result);
    }

    /**
     * Test that state is reset after render
     *
     * @return void
     */
    public function testStateResetAfterRender(): void
    {
        $this->Table->header(['ID', 'Name']);
        $this->Table->row([1, 'John']);

        $result1 = $this->Table->render();
        $this->assertStringContainsString('ID', $result1);
        $this->assertStringContainsString('John', $result1);

        // After render, state should be reset
        $result2 = $this->Table->render();
        $this->assertStringNotContainsString('ID', $result2);
        $this->assertStringNotContainsString('John', $result2);
    }

    /**
     * Test body method with options
     *
     * @return void
     */
    public function testBodyWithOptions(): void
    {
        $this->Table->body(['id' => 'sortable-items', 'class' => 'sortable']);
        $this->Table->row([1, 'John Doe']);
        $result = $this->Table->render();

        $this->assertStringContainsString('<tbody id="sortable-items" class="sortable">', $result);
    }

    /**
     * Test body options via render method
     *
     * @return void
     */
    public function testBodyOptionsViaRender(): void
    {
        $this->Table->row([1, 'John Doe']);
        $result = $this->Table->render([
            'body' => ['id' => 'table-body', 'data-controller' => 'sortable'],
        ]);

        $this->assertStringContainsString('id="table-body"', $result);
        $this->assertStringContainsString('data-controller="sortable"', $result);
    }

    /**
     * Test body options are merged when set via both methods
     *
     * @return void
     */
    public function testBodyOptionsMerged(): void
    {
        $this->Table->body(['id' => 'my-body']);
        $this->Table->row([1, 'John Doe']);
        $result = $this->Table->render([
            'body' => ['class' => 'highlight'],
        ]);

        $this->assertStringContainsString('id="my-body"', $result);
        $this->assertStringContainsString('class="highlight"', $result);
    }

    /**
     * Test body options are reset after render
     *
     * @return void
     */
    public function testBodyOptionsResetAfterRender(): void
    {
        $this->Table->body(['id' => 'sortable']);
        $this->Table->row([1, 'John']);
        $result1 = $this->Table->render();
        $this->assertStringContainsString('id="sortable"', $result1);

        // After render, body options should be reset
        $this->Table->row([2, 'Jane']);
        $result2 = $this->Table->render();
        $this->assertStringNotContainsString('id="sortable"', $result2);
    }

    /**
     * Test row with options
     *
     * @return void
     */
    public function testRowWithOptions(): void
    {
        $this->Table->row([1, 'John Doe'], ['id' => 'row-1', 'class' => 'highlight']);
        $result = $this->Table->render();

        $this->assertStringContainsString('<tr id="row-1" class="highlight">', $result);
    }

    /**
     * Test multiple rows with different options
     *
     * @return void
     */
    public function testMultipleRowsWithOptions(): void
    {
        $this->Table->row([1, 'John Doe'], ['data-id' => '1', 'class' => 'odd']);
        $this->Table->row([2, 'Jane Smith'], ['data-id' => '2', 'class' => 'even']);
        $result = $this->Table->render();

        $this->assertStringContainsString('data-id="1"', $result);
        $this->assertStringContainsString('data-id="2"', $result);
        $this->assertStringContainsString('class="odd"', $result);
        $this->assertStringContainsString('class="even"', $result);
    }

    /**
     * Test row with options and cell attributes combined
     *
     * @return void
     */
    public function testRowWithOptionsAndCellAttributes(): void
    {
        $this->Table->row(
            [
                [1, ['class' => 'id-cell']],
                ['John Doe', ['class' => 'name-cell']],
            ],
            ['id' => 'row-1', 'class' => 'active']
        );
        $result = $this->Table->render();

        $this->assertStringContainsString('<tr id="row-1" class="active">', $result);
        $this->assertStringContainsString('class="id-cell"', $result);
        $this->assertStringContainsString('class="name-cell"', $result);
    }

    /**
     * Test complete table with all options
     *
     * @return void
     */
    public function testCompleteTableWithAllOptions(): void
    {
        $this->Table->header(['ID', 'Name']);
        $this->Table->body(['id' => 'tbody-sortable']);
        $this->Table->row([1, 'John'], ['data-id' => '1']);
        $this->Table->row([2, 'Jane'], ['data-id' => '2']);

        $result = $this->Table->render([
            'table' => ['class' => 'table table-striped'],
            'wrapper' => ['class' => 'table-responsive my-wrapper'],
        ]);

        $this->assertStringContainsString('id="tbody-sortable"', $result);
        $this->assertStringContainsString('data-id="1"', $result);
        $this->assertStringContainsString('data-id="2"', $result);
        $this->assertStringContainsString('table-striped', $result);
        $this->assertStringContainsString('my-wrapper', $result);
    }
}
