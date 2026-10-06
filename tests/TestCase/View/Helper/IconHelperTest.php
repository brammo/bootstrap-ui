<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\Test\TestCase\View\Helper;

use Brammo\BootstrapUI\View\Helper\IconHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Brammo\BootstrapUI\View\Helper\IconHelper Test Case
 */
class IconHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Brammo\BootstrapUI\View\Helper\IconHelper
     */
    protected IconHelper $Icon;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Icon = new IconHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Icon);
        parent::tearDown();
    }

    /**
     * Test the Bootstrap Icons default
     *
     * @return void
     */
    public function testIconBootstrapDefault(): void
    {
        $result = $this->Icon->icon('house');

        $this->assertSame('<i class="bi bi-house"></i>', $result);
    }

    /**
     * Test a per-call Font Awesome override
     *
     * @return void
     */
    public function testIconFontAwesome(): void
    {
        $result = $this->Icon->icon('user', [
            'namespace' => 'fa-solid',
            'prefix' => 'fa',
        ]);

        $this->assertSame('<i class="fa-solid fa-user"></i>', $result);
    }

    /**
     * Test a per-call Tabler override
     *
     * @return void
     */
    public function testIconTabler(): void
    {
        $result = $this->Icon->icon('user', [
            'namespace' => 'ti',
            'prefix' => 'ti',
        ]);

        $this->assertSame('<i class="ti ti-user"></i>', $result);
    }

    /**
     * Test helper config as the default icon set
     *
     * @return void
     */
    public function testIconConfigDefaults(): void
    {
        $view = new View();
        $icon = new IconHelper($view, [
            'namespace' => 'fa-solid',
            'prefix' => 'fa',
        ]);

        $result = $icon->icon('user');

        $this->assertSame('<i class="fa-solid fa-user"></i>', $result);
    }

    /**
     * Test a per-call override of configured defaults
     *
     * @return void
     */
    public function testIconOverridesConfig(): void
    {
        $view = new View();
        $icon = new IconHelper($view, [
            'namespace' => 'fa-solid',
            'prefix' => 'fa',
        ]);

        $result = $icon->icon('user', [
            'namespace' => 'ti',
            'prefix' => 'ti',
        ]);

        $this->assertSame('<i class="ti ti-user"></i>', $result);
    }

    /**
     * Test an empty namespace omits the set class
     *
     * @return void
     */
    public function testIconEmptyNamespace(): void
    {
        $result = $this->Icon->icon('house', ['namespace' => '']);

        $this->assertSame('<i class="bi-house"></i>', $result);
    }

    /**
     * Test an empty prefix uses the icon name as the class
     *
     * @return void
     */
    public function testIconEmptyPrefix(): void
    {
        $result = $this->Icon->icon('house', [
            'namespace' => '',
            'prefix' => '',
        ]);

        $this->assertSame('<i class="house"></i>', $result);
    }

    /**
     * Test the size class
     *
     * @return void
     */
    public function testIconSize(): void
    {
        $result = $this->Icon->icon('house', ['size' => 'lg']);

        $this->assertSame('<i class="bi bi-house bi-lg"></i>', $result);
    }

    /**
     * Test that size is omitted when prefix is empty
     *
     * @return void
     */
    public function testIconSizeWithoutPrefix(): void
    {
        $result = $this->Icon->icon('house', [
            'namespace' => '',
            'prefix' => '',
            'size' => 'lg',
        ]);

        $this->assertSame('<i class="house"></i>', $result);
    }

    /**
     * Test appended classes and extra attributes
     *
     * @return void
     */
    public function testIconClassAndAttributes(): void
    {
        $result = $this->Icon->icon('house', [
            'class' => 'me-2',
            'data-custom' => 'attribute',
        ]);

        $this->assertSame('<i data-custom="attribute" class="bi bi-house me-2"></i>', $result);
    }

    /**
     * Test a custom tag
     *
     * @return void
     */
    public function testIconCustomTag(): void
    {
        $result = $this->Icon->icon('house', ['tag' => 'span']);

        $this->assertSame('<span class="bi bi-house"></span>', $result);
    }
}
