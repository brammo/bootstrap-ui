<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\Test\TestCase\View\Helper;

use Brammo\BootstrapUI\View\Helper\CardHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Brammo\BootstrapUI\View\Helper\CardHelper Test Case
 */
class CardHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Brammo\BootstrapUI\View\Helper\CardHelper
     */
    protected CardHelper $Card;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Card = new CardHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Card);
        parent::tearDown();
    }

    /**
     * Test render method with default options
     *
     * @return void
     */
    public function testRenderDefault(): void
    {
        $body = 'This is card body content';
        $result = $this->Card->render($body);

        $this->assertStringContainsString('class="card"', $result);
        $this->assertStringContainsString('class="card-body"', $result);
        $this->assertStringContainsString($body, $result);
        $this->assertStringNotContainsString('card-header', $result);
        $this->assertStringNotContainsString('card-footer', $result);
    }

    /**
     * Test render method with header
     *
     * @return void
     */
    public function testRenderWithHeader(): void
    {
        $body = 'Card content';
        $result = $this->Card->render($body, [
            'header' => 'Custom Header',
        ]);

        $this->assertStringContainsString('class="card-header"', $result);
        $this->assertStringContainsString('Custom Header', $result);
        $this->assertStringContainsString('class="card-body"', $result);
        $this->assertStringContainsString($body, $result);
    }

    /**
     * Test render method with footer
     *
     * @return void
     */
    public function testRenderWithFooter(): void
    {
        $body = 'Card content';
        $result = $this->Card->render($body, [
            'footer' => 'Card Footer',
        ]);

        $this->assertStringContainsString('class="card-footer"', $result);
        $this->assertStringContainsString('Card Footer', $result);
        $this->assertStringContainsString($body, $result);
    }

    /**
     * Test render with header, body, and footer together
     *
     * @return void
     */
    public function testRenderWithHeaderBodyAndFooter(): void
    {
        $body = 'Card body';
        $result = $this->Card->render($body, [
            'header' => 'Card Header',
            'footer' => 'Card Footer',
        ]);

        $this->assertStringContainsString('class="card-header"', $result);
        $this->assertStringContainsString('Card Header', $result);
        $this->assertStringContainsString($body, $result);
        $this->assertStringContainsString('class="card-footer"', $result);
        $this->assertStringContainsString('Card Footer', $result);
    }

    /**
     * Test section attribute options
     *
     * @return void
     */
    public function testRenderWithSectionAttributes(): void
    {
        $body = 'Card content';
        $result = $this->Card->render($body, [
            'header' => 'Header',
            'footer' => 'Footer',
            'headerAttrs' => ['class' => 'bg-primary text-white'],
            'bodyAttrs' => ['class' => 'p-4'],
            'footerAttrs' => ['class' => 'text-muted'],
        ]);

        $this->assertStringContainsString('class="bg-primary text-white"', $result);
        $this->assertStringContainsString('class="p-4"', $result);
        $this->assertStringContainsString('class="text-muted"', $result);
    }

    /**
     * Test render method with custom card class
     *
     * @return void
     */
    public function testRenderWithCustomClasses(): void
    {
        $body = 'Card content';
        $result = $this->Card->render($body, [
            'class' => 'border-primary',
        ]);

        // User class replaces default card class (mergeAttributes uses array +)
        $this->assertStringContainsString('class="border-primary"', $result);
        $this->assertStringContainsString($body, $result);
    }

    /**
     * Test default configuration
     *
     * @return void
     */
    public function testDefaultConfiguration(): void
    {
        $config = $this->Card->getConfig();

        $this->assertArrayHasKey('templates', $config);
        $this->assertArrayHasKey('card', $config['templates']);
        $this->assertArrayHasKey('header', $config['templates']);
        $this->assertArrayHasKey('body', $config['templates']);
        $this->assertArrayHasKey('footer', $config['templates']);
    }
}
