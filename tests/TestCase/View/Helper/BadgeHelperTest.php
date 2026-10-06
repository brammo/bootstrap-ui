<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\Test\TestCase\View\Helper;

use Brammo\BootstrapUI\View\Helper\BadgeHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Brammo\BootstrapUI\View\Helper\BadgeHelper Test Case
 */
class BadgeHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Brammo\BootstrapUI\View\Helper\BadgeHelper
     */
    protected BadgeHelper $Badge;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Badge = new BadgeHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Badge);
        parent::tearDown();
    }

    /**
     * Test render method with default options
     *
     * @return void
     */
    public function testRenderDefault(): void
    {
        $result = $this->Badge->render('New');

        $this->assertStringStartsWith('<span', $result);
        $this->assertStringEndsWith('</span>', $result);
        $this->assertStringContainsString('class="badge text-bg-secondary"', $result);
        $this->assertStringContainsString('New', $result);
        $this->assertStringNotContainsString('color=', $result);
    }

    /**
     * Test theme color wrappers
     *
     * @return void
     */
    public function testColorWrappers(): void
    {
        $colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];

        foreach ($colors as $color) {
            $result = $this->Badge->{$color}('Label');

            $this->assertStringContainsString('class="badge text-bg-' . $color . '"', $result);
            $this->assertStringContainsString('Label', $result);
        }
    }

    /**
     * Test a color wrapper forwards other options
     *
     * @return void
     */
    public function testColorWrapperWithPill(): void
    {
        $result = $this->Badge->success('Active', ['pill' => true]);

        $this->assertStringContainsString('class="badge text-bg-success rounded-pill"', $result);
        $this->assertStringContainsString('Active', $result);
    }

    /**
     * Test class without color does not add the default color class
     *
     * @return void
     */
    public function testRenderWithClassWithoutColor(): void
    {
        $result = $this->Badge->render('Custom', [
            'class' => 'bg-body-secondary text-primary ms-1',
        ]);

        $this->assertStringContainsString('class="badge bg-body-secondary text-primary ms-1"', $result);
        $this->assertStringNotContainsString('text-bg-secondary', $result);
        $this->assertStringContainsString('Custom', $result);
    }

    /**
     * Test class is appended when a color is set
     *
     * @return void
     */
    public function testRenderWithColorAndClass(): void
    {
        $result = $this->Badge->danger('3', ['class' => 'ms-1']);

        $this->assertStringContainsString('class="badge text-bg-danger ms-1"', $result);
        $this->assertStringContainsString('3', $result);
    }

    /**
     * Test pill option
     *
     * @return void
     */
    public function testRenderWithPill(): void
    {
        $result = $this->Badge->render('New', ['pill' => true]);

        $this->assertStringContainsString('class="badge text-bg-secondary rounded-pill"', $result);
    }

    /**
     * Test custom tag
     *
     * @return void
     */
    public function testRenderWithTag(): void
    {
        $result = $this->Badge->render('New', [
            'tag' => 'div',
            'id' => 'status',
        ]);

        $this->assertStringStartsWith('<div', $result);
        $this->assertStringEndsWith('</div>', $result);
        $this->assertStringContainsString('id="status"', $result);
        $this->assertStringContainsString('class="badge text-bg-secondary"', $result);
    }

    /**
     * Test visually hidden text
     *
     * @return void
     */
    public function testRenderWithVisuallyHidden(): void
    {
        $result = $this->Badge->render('99+', [
            'color' => 'danger',
            'visuallyHidden' => 'unread messages',
        ]);

        $this->assertStringContainsString('class="badge text-bg-danger"', $result);
        $this->assertStringContainsString('99+', $result);
        $this->assertStringContainsString('class="visually-hidden"', $result);
        $this->assertStringContainsString('unread messages', $result);
    }

    /**
     * Test positioned badge
     *
     * @return void
     */
    public function testRenderPositioned(): void
    {
        $result = $this->Badge->render('99+', [
            'color' => 'danger',
            'positioned' => true,
            'visuallyHidden' => 'unread messages',
        ]);

        $this->assertStringContainsString(
            'class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"',
            $result,
        );
        $this->assertStringContainsString('99+', $result);
        $this->assertStringContainsString('unread messages', $result);
    }

    /**
     * Test positioned badge with class and no color skips the default color
     *
     * @return void
     */
    public function testRenderPositionedWithClassWithoutColor(): void
    {
        $result = $this->Badge->render('1', [
            'positioned' => true,
            'class' => 'bg-warning text-dark',
        ]);

        $this->assertStringContainsString(
            'class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"',
            $result,
        );
        $this->assertStringNotContainsString('bg-secondary', $result);
        $this->assertStringNotContainsString('text-bg-', $result);
    }

    /**
     * Test positioned badge can opt out of the pill shape
     *
     * @return void
     */
    public function testRenderPositionedWithoutPill(): void
    {
        $result = $this->Badge->render('1', [
            'positioned' => true,
            'color' => 'danger',
            'pill' => false,
        ]);

        $this->assertStringContainsString(
            'class="position-absolute top-0 start-100 translate-middle badge bg-danger"',
            $result,
        );
        $this->assertStringNotContainsString('rounded-pill', $result);
    }

    /**
     * Test indicator dot omits visible text
     *
     * @return void
     */
    public function testRenderIndicator(): void
    {
        $result = $this->Badge->render('99+', [
            'indicator' => true,
            'color' => 'danger',
            'visuallyHidden' => 'New alerts',
        ]);

        $this->assertStringContainsString(
            'class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"',
            $result,
        );
        $this->assertStringNotContainsString('99+', $result);
        $this->assertStringNotContainsString('badge', $result);
        $this->assertStringNotContainsString('rounded-pill', $result);
        $this->assertStringContainsString('New alerts', $result);
    }

    /**
     * Test indicator dot with class and no color skips the default color
     *
     * @return void
     */
    public function testRenderIndicatorWithClassWithoutColor(): void
    {
        $result = $this->Badge->render('', [
            'indicator' => true,
            'class' => 'bg-success',
            'visuallyHidden' => 'New alerts',
        ]);

        $this->assertStringContainsString(
            'class="position-absolute top-0 start-100 translate-middle p-2 border border-light rounded-circle bg-success"',
            $result,
        );
        $this->assertStringNotContainsString('bg-secondary', $result);
    }

    /**
     * Test indicator dot uses the default color when no class is given
     *
     * @return void
     */
    public function testRenderIndicatorDefaultColor(): void
    {
        $result = $this->Badge->render('', ['indicator' => true]);

        $this->assertStringContainsString(
            'class="position-absolute top-0 start-100 translate-middle p-2 bg-secondary border border-light rounded-circle"',
            $result,
        );
    }

    /**
     * Test inline button badge
     *
     * @return void
     */
    public function testButtonInline(): void
    {
        $result = $this->Badge->button('Notifications', '4');

        $this->assertStringContainsString('<button type="button" class="btn btn-primary">', $result);
        $this->assertStringContainsString('Notifications', $result);
        $this->assertStringContainsString('class="badge text-bg-secondary"', $result);
        $this->assertStringContainsString('>4<', $result);
        $this->assertStringNotContainsString('position-relative', $result);
        $this->assertStringEndsWith('</button>', $result);
    }

    /**
     * Test positioned badge inside a button
     *
     * @return void
     */
    public function testButtonPositioned(): void
    {
        $result = $this->Badge->button('Inbox', '99+', [
            'positioned' => true,
            'color' => 'danger',
            'visuallyHidden' => 'unread messages',
        ]);

        $this->assertStringContainsString('class="btn btn-primary position-relative"', $result);
        $this->assertStringContainsString(
            'class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"',
            $result,
        );
        $this->assertStringContainsString('Inbox', $result);
        $this->assertStringContainsString('99+', $result);
        $this->assertStringContainsString('unread messages', $result);
    }

    /**
     * Test indicator dot inside a button
     *
     * @return void
     */
    public function testButtonIndicator(): void
    {
        $result = $this->Badge->button('Profile', 'ignored', [
            'indicator' => true,
            'color' => 'danger',
            'visuallyHidden' => 'New alerts',
        ]);

        $this->assertStringContainsString('class="btn btn-primary position-relative"', $result);
        $this->assertStringContainsString('Profile', $result);
        $this->assertStringNotContainsString('ignored', $result);
        $this->assertStringContainsString('New alerts', $result);
        $this->assertStringContainsString('bg-danger', $result);
    }

    /**
     * Test custom button attributes replace the default button class
     *
     * @return void
     */
    public function testButtonWithCustomAttributes(): void
    {
        $result = $this->Badge->button('Save', '1', [
            'buttonAttrs' => [
                'class' => 'btn btn-outline-primary',
                'id' => 'save-btn',
            ],
            'color' => 'success',
        ]);

        $this->assertStringContainsString('id="save-btn"', $result);
        $this->assertStringContainsString('class="btn btn-outline-primary"', $result);
        $this->assertStringNotContainsString('btn btn-primary', $result);
        $this->assertStringContainsString('class="badge text-bg-success"', $result);
    }

    /**
     * Test button badge class without color skips the default color
     *
     * @return void
     */
    public function testButtonWithBadgeClassWithoutColor(): void
    {
        $result = $this->Badge->button('Alerts', '2', [
            'class' => 'bg-dark text-white ms-2',
        ]);

        $this->assertStringContainsString('class="badge bg-dark text-white ms-2"', $result);
        $this->assertStringNotContainsString('text-bg-secondary', $result);
        $this->assertStringContainsString('class="btn btn-primary"', $result);
    }

    /**
     * Test badgeAttrs are applied to the inner badge
     *
     * @return void
     */
    public function testButtonWithBadgeAttributes(): void
    {
        $result = $this->Badge->button('Alerts', '2', [
            'badgeAttrs' => ['id' => 'alert-count'],
            'color' => 'info',
        ]);

        $this->assertStringContainsString('id="alert-count"', $result);
        $this->assertStringContainsString('class="badge text-bg-info"', $result);
    }

    /**
     * Test positioned button keeps a custom button class and adds position-relative
     *
     * @return void
     */
    public function testButtonPositionedWithCustomClass(): void
    {
        $result = $this->Badge->button('Inbox', '3', [
            'positioned' => true,
            'color' => 'danger',
            'buttonAttrs' => ['class' => 'btn btn-secondary'],
        ]);

        $this->assertStringContainsString('class="btn btn-secondary position-relative"', $result);
        $this->assertStringContainsString('bg-danger', $result);
    }

    /**
     * Test default configuration
     *
     * @return void
     */
    public function testDefaultConfiguration(): void
    {
        $config = $this->Badge->getConfig();

        $this->assertArrayHasKey('templates', $config);
        $this->assertArrayHasKey('badge', $config['templates']);
        $this->assertArrayHasKey('button', $config['templates']);
        $this->assertArrayHasKey('visuallyHidden', $config['templates']);
    }
}
