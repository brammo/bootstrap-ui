<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\Test\TestCase\View\Helper;

use Brammo\BootstrapUI\View\Helper\CarouselHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;

/**
 * Brammo\BootstrapUI\View\Helper\CarouselHelper Test Case
 */
class CarouselHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Brammo\BootstrapUI\View\Helper\CarouselHelper
     */
    protected CarouselHelper $Carousel;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Carousel = new CarouselHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Carousel);
        parent::tearDown();
    }

    /**
     * Test render with a basic slide and controls
     *
     * @return void
     */
    public function testRenderBasicWithControls(): void
    {
        $image = '<img src="/slide1.jpg" class="d-block w-100" alt="Slide 1">';
        $result = $this->Carousel
            ->add($image)
            ->add('<img src="/slide2.jpg" class="d-block w-100" alt="Slide 2">')
            ->render(['id' => 'carousel-basic']);

        $this->assertStringContainsString('id="carousel-basic"', $result);
        $this->assertStringContainsString('class="carousel slide"', $result);
        $this->assertStringContainsString('class="carousel-inner"', $result);
        $this->assertEquals(1, substr_count($result, 'class="carousel-item active"'));
        $this->assertEquals(1, substr_count($result, 'class="carousel-item"'));
        $this->assertStringContainsString($image, $result);
        $this->assertStringContainsString('class="carousel-control-prev"', $result);
        $this->assertStringContainsString('class="carousel-control-next"', $result);
        $this->assertStringContainsString('data-bs-target="#carousel-basic"', $result);
        $this->assertStringContainsString('data-bs-slide="prev"', $result);
        $this->assertStringContainsString('data-bs-slide="next"', $result);
        $this->assertStringContainsString('carousel-control-prev-icon', $result);
        $this->assertStringContainsString('carousel-control-next-icon', $result);
        $this->assertStringContainsString('class="visually-hidden">Previous</span>', $result);
        $this->assertStringContainsString('class="visually-hidden">Next</span>', $result);
        $this->assertStringNotContainsString('carousel-indicators', $result);
    }

    /**
     * Test render without controls
     *
     * @return void
     */
    public function testRenderWithoutControls(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render(['controls' => false]);

        $this->assertStringContainsString('class="carousel slide"', $result);
        $this->assertStringNotContainsString('carousel-control-prev', $result);
        $this->assertStringNotContainsString('carousel-control-next', $result);
    }

    /**
     * Test render with indicators
     *
     * @return void
     */
    public function testRenderWithIndicators(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->add('<img src="/slide2.jpg" alt="Slide 2">')
            ->add('<img src="/slide3.jpg" alt="Slide 3">')
            ->render(['id' => 'carousel-indicators', 'indicators' => true]);

        $this->assertStringContainsString('class="carousel-indicators"', $result);
        $this->assertStringContainsString('data-bs-slide-to="0"', $result);
        $this->assertStringContainsString('data-bs-slide-to="1"', $result);
        $this->assertStringContainsString('data-bs-slide-to="2"', $result);
        $this->assertStringContainsString('aria-label="Slide 1"', $result);
        $this->assertStringContainsString('aria-label="Slide 2"', $result);
        $this->assertStringContainsString('aria-label="Slide 3"', $result);
        $this->assertStringContainsString('class="active"', $result);
        $this->assertStringContainsString('aria-current="true"', $result);
    }

    /**
     * Test render with crossfade
     *
     * @return void
     */
    public function testRenderWithCrossfade(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render(['id' => 'carousel-fade', 'crossfade' => true]);

        $this->assertStringContainsString('class="carousel slide carousel-fade"', $result);
    }

    /**
     * Test render with autoplay on load
     *
     * @return void
     */
    public function testRenderWithAutoplayOnLoad(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render(['id' => 'carousel-autoplay', 'autoplay' => 'carousel']);

        $this->assertStringContainsString('data-bs-ride="carousel"', $result);
    }

    /**
     * Test render with autoplay after first interaction
     *
     * @return void
     */
    public function testRenderWithAutoplayAfterInteraction(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render(['id' => 'carousel-ride', 'autoplay' => true]);

        $this->assertStringContainsString('data-bs-ride="true"', $result);
    }

    /**
     * Test render with string caption
     *
     * @return void
     */
    public function testRenderWithStringCaption(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">', [
                'caption' => '<h5>Slide title</h5><p>Slide text</p>',
            ])
            ->render(['id' => 'carousel-caption', 'controls' => false]);

        $this->assertStringContainsString('class="carousel-caption d-none d-md-block"', $result);
        $this->assertStringContainsString('<h5>Slide title</h5><p>Slide text</p>', $result);
    }

    /**
     * Test render with array caption
     *
     * @return void
     */
    public function testRenderWithArrayCaption(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">', [
                'caption' => [
                    'title' => 'First slide label',
                    'text' => 'Some representative placeholder content.',
                ],
            ])
            ->render(['id' => 'carousel-caption-array', 'controls' => false]);

        $this->assertStringContainsString('<h5>First slide label</h5>', $result);
        $this->assertStringContainsString('<p>Some representative placeholder content.</p>', $result);
    }

    /**
     * Test render with custom caption attributes
     *
     * @return void
     */
    public function testRenderWithCaptionAttributes(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">', [
                'caption' => 'Visible caption',
                'captionAttrs' => ['class' => 'carousel-caption'],
            ])
            ->render(['id' => 'carousel-caption-attrs', 'controls' => false]);

        $this->assertStringContainsString('class="carousel-caption"', $result);
        $this->assertStringNotContainsString('d-none d-md-block', $result);
        $this->assertStringContainsString('Visible caption', $result);
    }

    /**
     * Test render with per-slide interval
     *
     * @return void
     */
    public function testRenderWithItemInterval(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">', ['interval' => 10000])
            ->add('<img src="/slide2.jpg" alt="Slide 2">', ['interval' => 2000])
            ->render(['id' => 'carousel-interval', 'autoplay' => 'carousel']);

        $this->assertStringContainsString('data-bs-interval="10000"', $result);
        $this->assertStringContainsString('data-bs-interval="2000"', $result);
    }

    /**
     * Test explicit active slide
     *
     * @return void
     */
    public function testRenderWithExplicitActiveSlide(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->add('<img src="/slide2.jpg" alt="Slide 2">', ['active' => true])
            ->render(['id' => 'carousel-active', 'controls' => false]);

        $this->assertMatchesRegularExpression(
            '/<div class="carousel-item"><img src="\/slide1\.jpg"/',
            $result,
        );
        $this->assertMatchesRegularExpression(
            '/<div class="carousel-item active"><img src="\/slide2\.jpg"/',
            $result,
        );
    }

    /**
     * Test render clears items after rendering
     *
     * @return void
     */
    public function testRenderClearsItems(): void
    {
        $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render(['controls' => false]);

        $result = $this->Carousel->render(['controls' => false]);

        $this->assertSame('', $result);
    }

    /**
     * Test id is required when controls are enabled
     *
     * @return void
     */
    public function testRenderRequiresIdForControls(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Carousel `id` is required when controls or indicators are enabled.');

        $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render(['controls' => true]);
    }

    /**
     * Test id is required when indicators are enabled
     *
     * @return void
     */
    public function testRenderRequiresIdForIndicators(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render(['controls' => false, 'indicators' => true]);
    }

    /**
     * Test render with carousel and inner attributes
     *
     * @return void
     */
    public function testRenderWithElementAttributes(): void
    {
        $result = $this->Carousel
            ->add('<img src="/slide1.jpg" alt="Slide 1">')
            ->render([
                'controls' => false,
                'carouselAttrs' => ['class' => 'mb-4', 'data-test' => 'carousel'],
                'innerAttrs' => ['id' => 'carousel-inner'],
            ]);

        $this->assertStringContainsString('class="carousel slide mb-4"', $result);
        $this->assertStringContainsString('data-test="carousel"', $result);
        $this->assertStringContainsString('id="carousel-inner"', $result);
    }

    /**
     * Test default configuration
     *
     * @return void
     */
    public function testDefaultConfiguration(): void
    {
        $config = $this->Carousel->getConfig();

        $this->assertTrue($config['controls']);
        $this->assertFalse($config['indicators']);
        $this->assertFalse($config['crossfade']);
        $this->assertFalse($config['autoplay']);
        $this->assertArrayHasKey('carousel', $config['templates']);
        $this->assertArrayHasKey('indicator', $config['templates']);
        $this->assertArrayHasKey('controlPrev', $config['templates']);
    }
}
