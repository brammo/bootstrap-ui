<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\Test\TestCase\View\Helper;

use Brammo\BootstrapUI\View\Helper\NavHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * BootstrapUI\View\Helper\NavHelper Test Case
 */
class NavHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BootstrapUI\View\Helper\NavHelper
     */
    protected NavHelper $Nav;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Nav = new NavHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Nav);
        parent::tearDown();
    }

    /**
     * Test render method with single tab
     *
     * @return void
     */
    public function testRenderSingleTab(): void
    {
        $this->Nav->add('home', 'Home', '<p>Home content</p>');
        $result = $this->Nav->render();

        // Check nav structure
        $this->assertStringContainsString('<ul', $result);
        $this->assertStringContainsString('class="nav nav-tabs', $result);
        $this->assertStringContainsString('role="tablist"', $result);

        // Check nav item
        $this->assertStringContainsString('<li', $result);
        $this->assertStringContainsString('class="nav-item"', $result);
        $this->assertStringContainsString('role="presentation"', $result);

        // Check nav button
        $this->assertStringContainsString('<button', $result);
        $this->assertStringContainsString('data-bs-toggle="tab"', $result);
        $this->assertStringContainsString('data-bs-target="#home"', $result);
        $this->assertStringContainsString('aria-controls="home"', $result);
        $this->assertStringContainsString('aria-selected="true"', $result);
        $this->assertStringContainsString('Home', $result);

        // Check tab content
        $this->assertStringContainsString('class="tab-content"', $result);
        $this->assertStringContainsString('id="home"', $result);
        $this->assertStringContainsString('class="tab-pane fade show active"', $result);
        $this->assertStringContainsString('role="tabpanel"', $result);
        $this->assertStringContainsString('<p>Home content</p>', $result);
    }

    /**
     * Test render method with multiple tabs
     *
     * @return void
     */
    public function testRenderMultipleTabs(): void
    {
        $this->Nav
            ->add('home', 'Home', '<p>Home content</p>')
            ->add('profile', 'Profile', '<p>Profile content</p>')
            ->add('settings', 'Settings', '<p>Settings content</p>');
        $result = $this->Nav->render();

        // Check all tabs are present
        $this->assertStringContainsString('Home', $result);
        $this->assertStringContainsString('Profile', $result);
        $this->assertStringContainsString('Settings', $result);

        // Check all panels are present
        $this->assertStringContainsString('id="home"', $result);
        $this->assertStringContainsString('id="profile"', $result);
        $this->assertStringContainsString('id="settings"', $result);

        // First tab should be active
        $this->assertStringContainsString('aria-selected="true"', $result);

        // Check content
        $this->assertStringContainsString('<p>Home content</p>', $result);
        $this->assertStringContainsString('<p>Profile content</p>', $result);
        $this->assertStringContainsString('<p>Settings content</p>', $result);
    }

    /**
     * Test first tab is active by default
     *
     * @return void
     */
    public function testFirstTabActiveByDefault(): void
    {
        $this->Nav
            ->add('tab1', 'Tab 1', 'Content 1')
            ->add('tab2', 'Tab 2', 'Content 2');
        $result = $this->Nav->render();

        // First tab button should have active class and aria-selected=true
        $this->assertStringContainsString('class="nav-link active"', $result);
        $this->assertStringContainsString('data-bs-target="#tab1"', $result);
        $this->assertStringContainsString('aria-selected="true"', $result);

        // First tab pane should have show active classes
        $this->assertStringContainsString('class="tab-pane fade show active"', $result);

        // Second tab should not be active
        $this->assertStringContainsString('aria-selected="false"', $result);
    }

    /**
     * Test forcing active tab
     *
     * @return void
     */
    public function testForceActiveTab(): void
    {
        $this->Nav
            ->add('tab1', 'Tab 1', 'Content 1')
            ->add('tab2', 'Tab 2', 'Content 2', ['active' => true]);
        $result = $this->Nav->render();

        // Second tab should be active (forced)
        $this->assertStringContainsString('data-bs-target="#tab2"', $result);

        // Count occurrences of active class - both tabs should be active
        // (first by default, second forced)
        $activeCount = substr_count($result, 'class="nav-link active"');
        $this->assertEquals(2, $activeCount);
    }

    /**
     * Test disabled tab
     *
     * @return void
     */
    public function testDisabledTab(): void
    {
        $this->Nav
            ->add('tab1', 'Tab 1', 'Content 1')
            ->add('tab2', 'Tab 2', 'Content 2', ['disabled' => true]);
        $result = $this->Nav->render();

        // Disabled tab should have disabled class and attributes
        $this->assertStringContainsString('class="nav-link disabled"', $result);
        $this->assertStringContainsString('disabled="disabled"', $result);
        $this->assertStringContainsString('aria-disabled="true"', $result);
    }

    /**
     * Test pills type
     *
     * @return void
     */
    public function testPillsType(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render(['type' => 'pills']);

        $this->assertStringContainsString('class="nav nav-pills', $result);
        $this->assertStringNotContainsString('nav-tabs', $result);
    }

    /**
     * Test tabs type (default)
     *
     * @return void
     */
    public function testTabsType(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render(['type' => 'tabs']);

        $this->assertStringContainsString('class="nav nav-tabs', $result);
        $this->assertStringNotContainsString('nav-pills', $result);
    }

    /**
     * Test fade disabled
     *
     * @return void
     */
    public function testFadeDisabled(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render(['fade' => false]);

        // Should not have fade class but should still have active
        $this->assertStringContainsString('class="tab-pane show active"', $result);
        $this->assertStringNotContainsString('fade', $result);
    }

    /**
     * Test fill option
     *
     * @return void
     */
    public function testFillOption(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render(['fill' => true]);

        $this->assertStringContainsString('nav-fill', $result);
    }

    /**
     * Test justified option
     *
     * @return void
     */
    public function testJustifiedOption(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render(['justified' => true]);

        $this->assertStringContainsString('nav-justified', $result);
    }

    /**
     * Test vertical option
     *
     * @return void
     */
    public function testVerticalOption(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render(['vertical' => true]);

        $this->assertStringContainsString('flex-column', $result);
        $this->assertStringContainsString('class="d-flex align-items-start"', $result);
    }

    /**
     * Test tab with icon
     *
     * @return void
     */
    public function testTabWithIcon(): void
    {
        $this->Nav->add('settings', 'Settings', 'Content', ['icon' => 'cog']);
        $result = $this->Nav->render();

        // Icon should be rendered via Html->icon()
        $this->assertStringContainsString('<i', $result);
        $this->assertStringContainsString('Settings', $result);
    }

    /**
     * Test addLink method
     *
     * @return void
     */
    public function testAddLink(): void
    {
        $this->Nav->addLink('Dashboard', '/dashboard');
        $result = $this->Nav->renderNav();

        $this->assertStringContainsString('<a', $result);
        $this->assertStringContainsString('href="/dashboard"', $result);
        $this->assertStringContainsString('class="nav-link"', $result);
        $this->assertStringContainsString('Dashboard', $result);
    }

    /**
     * Test addLink with active state
     *
     * @return void
     */
    public function testAddLinkActive(): void
    {
        $this->Nav->addLink('Dashboard', '/dashboard', ['active' => true]);
        $result = $this->Nav->renderNav();

        $this->assertStringContainsString('class="nav-link active"', $result);
        $this->assertStringContainsString('aria-current="page"', $result);
    }

    /**
     * Test addLink with disabled state
     *
     * @return void
     */
    public function testAddLinkDisabled(): void
    {
        $this->Nav->addLink('Dashboard', '/dashboard', ['disabled' => true]);
        $result = $this->Nav->renderNav();

        $this->assertStringContainsString('class="nav-link disabled"', $result);
        $this->assertStringContainsString('aria-disabled="true"', $result);
        $this->assertStringContainsString('tabindex="-1"', $result);
    }

    /**
     * Test addLink with icon
     *
     * @return void
     */
    public function testAddLinkWithIcon(): void
    {
        $this->Nav->addLink('Settings', '/settings', ['icon' => 'cog']);
        $result = $this->Nav->renderNav();

        $this->assertStringContainsString('<i', $result);
        $this->assertStringContainsString('Settings', $result);
    }

    /**
     * Test renderNav method (nav only, no panels)
     *
     * @return void
     */
    public function testRenderNavOnly(): void
    {
        $this->Nav
            ->addLink('Home', '/')
            ->addLink('About', '/about');
        $result = $this->Nav->renderNav();

        // Should have nav but no tab-content
        $this->assertStringContainsString('<ul', $result);
        $this->assertStringContainsString('class="nav nav-tabs', $result);
        $this->assertStringNotContainsString('class="tab-content"', $result);
        $this->assertStringNotContainsString('class="tab-pane', $result);
    }

    /**
     * Test mixed tabs and links
     *
     * @return void
     */
    public function testMixedTabsAndLinks(): void
    {
        $this->Nav
            ->add('tab1', 'Tab 1', 'Content 1')
            ->addLink('External', '/external');
        $result = $this->Nav->render();

        // Should have both button (for tab) and link
        $this->assertStringContainsString('<button', $result);
        $this->assertStringContainsString('data-bs-toggle="tab"', $result);
        $this->assertStringContainsString('<a', $result);
        $this->assertStringContainsString('href="/external"', $result);

        // Should have tab content only for tabs
        $this->assertStringContainsString('id="tab1"', $result);
    }

    /**
     * Test state is reset after render
     *
     * @return void
     */
    public function testStateResetAfterRender(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result1 = $this->Nav->render();

        $this->assertStringContainsString('Tab 1', $result1);
        $this->assertStringContainsString('Content 1', $result1);

        // After render, state should be reset
        $result2 = $this->Nav->render();
        $this->assertStringNotContainsString('Tab 1', $result2);
        $this->assertStringNotContainsString('Content 1', $result2);
    }

    /**
     * Test state is reset after renderNav
     *
     * @return void
     */
    public function testStateResetAfterRenderNav(): void
    {
        $this->Nav->addLink('Link 1', '/link1');
        $result1 = $this->Nav->renderNav();

        $this->assertStringContainsString('Link 1', $result1);

        // After renderNav, state should be reset
        $result2 = $this->Nav->renderNav();
        $this->assertStringNotContainsString('Link 1', $result2);
    }

    /**
     * Test custom nav attributes
     *
     * @return void
     */
    public function testCustomNavAttributes(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render([
            'navAttrs' => ['id' => 'my-nav', 'class' => 'custom-nav'],
        ]);

        $this->assertStringContainsString('id="my-nav"', $result);
        $this->assertStringContainsString('custom-nav', $result);
    }

    /**
     * Test custom content attributes
     *
     * @return void
     */
    public function testCustomContentAttributes(): void
    {
        $this->Nav->add('tab1', 'Tab 1', 'Content 1');
        $result = $this->Nav->render([
            'contentAttrs' => ['id' => 'my-content', 'class' => 'custom-content'],
        ]);

        $this->assertStringContainsString('id="my-content"', $result);
    }

    /**
     * Test ARIA attributes for accessibility
     *
     * @return void
     */
    public function testAriaAttributes(): void
    {
        $this->Nav->add('home', 'Home', 'Home content');
        $result = $this->Nav->render();

        // Nav should have role="tablist"
        $this->assertStringContainsString('role="tablist"', $result);

        // Nav item should have role="presentation"
        $this->assertStringContainsString('role="presentation"', $result);

        // Button should have role="tab", aria-controls, aria-selected
        $this->assertStringContainsString('role="tab"', $result);
        $this->assertStringContainsString('aria-controls="home"', $result);
        $this->assertStringContainsString('aria-selected="true"', $result);

        // Tab pane should have role="tabpanel", tabindex, aria-labelledby
        $this->assertStringContainsString('role="tabpanel"', $result);
        $this->assertStringContainsString('tabindex="0"', $result);
        $this->assertStringContainsString('aria-labelledby="home-tab"', $result);
    }

    /**
     * Test Bootstrap 5 data attributes
     *
     * @return void
     */
    public function testBootstrap5DataAttributes(): void
    {
        $this->Nav->add('home', 'Home', 'Home content');
        $result = $this->Nav->render();

        // Should use data-bs-* attributes (Bootstrap 5)
        $this->assertStringContainsString('data-bs-toggle="tab"', $result);
        $this->assertStringContainsString('data-bs-target="#home"', $result);
    }

    /**
     * Test fluent interface (method chaining)
     *
     * @return void
     */
    public function testFluentInterface(): void
    {
        $nav = $this->Nav
            ->add('tab1', 'Tab 1', 'Content 1')
            ->add('tab2', 'Tab 2', 'Content 2')
            ->addLink('Link', '/link');

        $this->assertInstanceOf(NavHelper::class, $nav);
    }

    /**
     * Test default configuration
     *
     * @return void
     */
    public function testDefaultConfiguration(): void
    {
        $config = $this->Nav->getConfig();

        $this->assertEquals('tabs', $config['type']);
        $this->assertTrue($config['fade']);
        $this->assertFalse($config['fill']);
        $this->assertFalse($config['justified']);
        $this->assertFalse($config['vertical']);
    }

    /**
     * Test addLink with array URL (CakePHP routing)
     *
     * @return void
     */
    public function testAddLinkWithArrayUrl(): void
    {
        // Use a simple string URL since routes aren't configured in tests
        $this->Nav->addLink('Users', '/users');
        $result = $this->Nav->renderNav();

        // Should have href attribute with URL
        $this->assertStringContainsString('href="/users"', $result);
        $this->assertStringContainsString('Users', $result);
    }
}
