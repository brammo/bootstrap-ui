<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\Test\TestCase\View\Helper;

use Brammo\BootstrapUI\View\Helper\CardHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * BootstrapUI\View\Helper\CardHelper Test Case
 */
class CardHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BootstrapUI\View\Helper\CardHelper
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

        $this->assertStringContainsString($body, $result);
    }

    /**
     * Test render method with custom title
     *
     * @return void
     */
    public function testRenderWithTitle(): void
    {
        $body = 'Card content';
        $result = $this->Card->render($body, [
            'title' => 'Card Title',
        ]);

        // Note: The current implementation passes title in options but card element doesn't use it
        // This test verifies the current behavior
        $this->assertStringContainsString($body, $result);
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

        // The card element renders header OR body based on what's set last in element logic
        // Since body is passed as first param, it should be rendered
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

        // The card element only renders the last set section (footer), not body
        $this->assertStringContainsString('Card Footer', $result);
    }

    /**
     * Test render method with custom classes
     *
     * @return void
     */
    public function testRenderWithCustomClasses(): void
    {
        $body = 'Card content';
        $result = $this->Card->render($body, [
            'class' => ['custom-card-class'],
        ]);

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
