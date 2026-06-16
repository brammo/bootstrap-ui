<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use InvalidArgumentException;

/**
 * Carousel Helper
 *
 * Renders Bootstrap 5 carousel slideshows with optional controls, indicators,
 * crossfade transitions, autoplay, and per-slide captions.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 */
class CarouselHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default config for the helper.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'controls' => true,
        'indicators' => false,
        'crossfade' => false,
        'autoplay' => false,
        'prevLabel' => 'Previous',
        'nextLabel' => 'Next',
        'templates' => [
            'carousel' => '<div{{attrs}}>{{content}}</div>',
            'inner' => '<div{{attrs}}>{{content}}</div>',
            'item' => '<div{{attrs}}>{{content}}</div>',
            'caption' => '<div{{attrs}}>{{content}}</div>',
            'indicators' => '<div{{attrs}}>{{content}}</div>',
            'indicator' => '<button{{attrs}}></button>',
            'controlPrev' => '<button{{attrs}}>{{content}}</button>',
            'controlNext' => '<button{{attrs}}>{{content}}</button>',
            'controlIcon' => '<span{{attrs}}></span>',
            'controlLabel' => '<span{{attrs}}>{{content}}</span>',
        ],
    ];

    /**
     * Default attributes for the templates
     *
     * @var array<string, array<string, string>>
     */
    protected array $_defaultAttributes = [
        'carousel' => [
            'class' => 'carousel slide',
        ],
        'inner' => [
            'class' => 'carousel-inner',
        ],
        'item' => [
            'class' => 'carousel-item',
        ],
        'caption' => [
            'class' => 'carousel-caption d-none d-md-block',
        ],
        'indicators' => [
            'class' => 'carousel-indicators',
        ],
        'indicator' => [
            'type' => 'button',
        ],
        'controlPrev' => [
            'class' => 'carousel-control-prev',
            'type' => 'button',
            'data-bs-slide' => 'prev',
        ],
        'controlNext' => [
            'class' => 'carousel-control-next',
            'type' => 'button',
            'data-bs-slide' => 'next',
        ],
        'controlIcon' => [
            'aria-hidden' => 'true',
        ],
        'controlLabel' => [
            'class' => 'visually-hidden',
        ],
    ];

    /**
     * The carousel items collection
     *
     * @var array<int, array{content: string, options: array<string, mixed>}>
     */
    protected array $items = [];

    /**
     * Add a carousel slide
     *
     * Options:
     * - `caption`: Caption content as a string, or an array with `title` and `text` keys
     * - `captionAttrs`: HTML attributes for the caption element
     * - `active`: Mark this slide as active (default: first slide is active)
     * - `interval`: Per-slide autoplay delay in milliseconds (`data-bs-interval`)
     * - Any other options are used as HTML attributes for the slide element
     *
     * @param string $content Slide content (typically an image element)
     * @param array<string, mixed> $options Options for the slide
     * @return $this
     */
    public function add(string $content, array $options = [])
    {
        $this->items[] = [
            'content' => $content,
            'options' => $options,
        ];

        return $this;
    }

    /**
     * Render the carousel
     *
     * Options:
     * - `id`: Unique carousel element ID (required when controls or indicators are enabled)
     * - `controls`: Show previous/next controls (default: true)
     * - `indicators`: Show slide indicators (default: false)
     * - `crossfade`: Use fade transition instead of slide (default: false)
     * - `autoplay`: Autoplay behavior — `false`, `true` (after first interaction), or `'carousel'` (on load)
     * - `carouselAttrs`: HTML attributes for the carousel element
     * - `innerAttrs`: HTML attributes for the carousel-inner element
     * - `captionAttrs`: Default HTML attributes for caption elements
     *
     * @param array<string, mixed> $options Options for rendering
     * @return string
     */
    public function render(array $options = []): string
    {
        if (empty($this->items)) {
            $this->items = [];

            return '';
        }

        /** @var bool $controls */
        $controls = $options['controls'] ?? $this->getConfig('controls');
        /** @var bool $indicators */
        $indicators = $options['indicators'] ?? $this->getConfig('indicators');
        /** @var bool $crossfade */
        $crossfade = $options['crossfade'] ?? $this->getConfig('crossfade');
        /** @var bool|string $autoplay */
        $autoplay = $options['autoplay'] ?? $this->getConfig('autoplay');
        /** @var string|null $id */
        $id = $options['id'] ?? null;
        /** @var array<string, mixed> $carouselAttrs */
        $carouselAttrs = $options['carouselAttrs'] ?? [];
        /** @var array<string, mixed> $innerAttrs */
        $innerAttrs = $options['innerAttrs'] ?? [];
        /** @var array<string, mixed> $defaultCaptionAttrs */
        $defaultCaptionAttrs = $options['captionAttrs'] ?? [];

        if (($controls || $indicators) && ($id === null || $id === '')) {
            throw new InvalidArgumentException('Carousel `id` is required when controls or indicators are enabled.');
        }

        $templater = $this->templater();
        $activeIndex = $this->getActiveItemIndex();

        $carouselAttributes = $this->buildCarouselAttributes($carouselAttrs, $crossfade, $autoplay);
        $inner = $this->renderInner($activeIndex, $innerAttrs, $defaultCaptionAttrs);

        $content = '';
        if ($indicators && $id) {
            $content .= $this->renderIndicators($id, $activeIndex);
        }
        $content .= $inner;
        if ($controls && $id) {
            $content .= $this->renderControls($id);
        }

        $this->items = [];

        if ($id !== null && $id !== '') {
            $carouselAttributes['id'] = $id;
        }

        return $templater->format('carousel', [
            'attrs' => $templater->formatAttributes($carouselAttributes),
            'content' => $content,
        ]);
    }

    /**
     * Build carousel element attributes
     *
     * @param array<string, mixed> $attrs User-provided carousel attributes
     * @param bool $crossfade Whether crossfade is enabled
     * @param bool|string $autoplay Autoplay option value
     * @return array<string, mixed>
     */
    protected function buildCarouselAttributes(
        array $attrs,
        bool $crossfade,
        bool|string $autoplay,
    ): array {
        $carouselClasses = ['carousel', 'slide'];
        if ($crossfade) {
            $carouselClasses[] = 'carousel-fade';
        }
        $extraClass = isset($attrs['class']) ? ' ' . $attrs['class'] : '';
        unset($attrs['class']);

        $carouselAttrs = $this->mergeAttributes('carousel', $attrs);
        $carouselAttrs['class'] = implode(' ', $carouselClasses) . $extraClass;

        if ($autoplay === 'carousel') {
            $carouselAttrs['data-bs-ride'] = 'carousel';
        } elseif ($autoplay === true) {
            $carouselAttrs['data-bs-ride'] = 'true';
        }

        return $carouselAttrs;
    }

    /**
     * Render carousel-inner with slides
     *
     * @param int $activeIndex Index of the active slide
     * @param array<string, mixed> $innerAttrs Attributes for carousel-inner
     * @param array<string, mixed> $defaultCaptionAttrs Default caption attributes
     * @return string
     */
    protected function renderInner(int $activeIndex, array $innerAttrs, array $defaultCaptionAttrs): string
    {
        $templater = $this->templater();
        $slides = [];

        foreach ($this->items as $index => $item) {
            /** @var string $content */
            $content = $item['content'];
            /** @var array<string, mixed> $options */
            $options = $item['options'];

            /** @var string|array<string, string>|null $caption */
            $caption = $options['caption'] ?? null;
            /** @var array<string, mixed> $itemCaptionAttrs */
            $itemCaptionAttrs = $options['captionAttrs'] ?? [];
            /** @var int|null $interval */
            $interval = $options['interval'] ?? null;
            unset($options['caption'], $options['captionAttrs'], $options['active'], $options['interval']);

            $itemClasses = ['carousel-item'];
            if ($index === $activeIndex) {
                $itemClasses[] = 'active';
            }
            $extraClass = isset($options['class']) ? ' ' . $options['class'] : '';
            unset($options['class']);

            $itemAttrs = $this->mergeAttributes('item', $options);
            $itemAttrs['class'] = implode(' ', $itemClasses) . $extraClass;

            if ($interval !== null) {
                $itemAttrs['data-bs-interval'] = (string)$interval;
            }

            $slideContent = $content;
            if ($caption !== null) {
                $slideContent .= $this->renderCaption($caption, $itemCaptionAttrs + $defaultCaptionAttrs);
            }

            $slides[] = $templater->format('item', [
                'attrs' => $templater->formatAttributes($itemAttrs),
                'content' => $slideContent,
            ]);
        }

        $extraInnerClass = isset($innerAttrs['class']) ? ' ' . $innerAttrs['class'] : '';
        unset($innerAttrs['class']);
        $innerAttributes = $this->mergeAttributes('inner', $innerAttrs);
        $innerAttributes['class'] = 'carousel-inner' . $extraInnerClass;

        return $templater->format('inner', [
            'attrs' => $templater->formatAttributes($innerAttributes),
            'content' => implode("\n", $slides),
        ]);
    }

    /**
     * Render a slide caption
     *
     * @param string|array<string, string> $caption Caption content
     * @param array<string, mixed> $captionAttrs Caption element attributes
     * @return string
     */
    protected function renderCaption(string|array $caption, array $captionAttrs): string
    {
        $templater = $this->templater();

        if (is_string($caption)) {
            $captionContent = $caption;
        } else {
            $captionContent = '';
            if (isset($caption['title'])) {
                $captionContent .= '<h5>' . $caption['title'] . '</h5>';
            }
            if (isset($caption['text'])) {
                $captionContent .= '<p>' . $caption['text'] . '</p>';
            }
        }

        $extraClass = $captionAttrs['class'] ?? 'carousel-caption d-none d-md-block';
        unset($captionAttrs['class']);
        $attrs = $this->mergeAttributes('caption', $captionAttrs);
        $attrs['class'] = $extraClass;

        return $templater->format('caption', [
            'attrs' => $templater->formatAttributes($attrs),
            'content' => $captionContent,
        ]);
    }

    /**
     * Render slide indicators
     *
     * @param string $id Carousel element ID
     * @param int $activeIndex Index of the active slide
     * @return string
     */
    protected function renderIndicators(string $id, int $activeIndex): string
    {
        $templater = $this->templater();
        $buttons = [];
        $target = '#' . $id;

        foreach ($this->items as $index => $item) {
            $indicatorAttrs = $this->mergeAttributes('indicator', []);
            $indicatorAttrs['data-bs-target'] = $target;
            $indicatorAttrs['data-bs-slide-to'] = (string)$index;
            $indicatorAttrs['aria-label'] = 'Slide ' . ($index + 1);

            if ($index === $activeIndex) {
                $indicatorAttrs['class'] = 'active';
                $indicatorAttrs['aria-current'] = 'true';
            }

            $buttons[] = $templater->format('indicator', [
                'attrs' => $templater->formatAttributes($indicatorAttrs),
            ]);
        }

        $indicatorsAttrs = $this->mergeAttributes('indicators', []);

        return $templater->format('indicators', [
            'attrs' => $templater->formatAttributes($indicatorsAttrs),
            'content' => implode("\n", $buttons),
        ]);
    }

    /**
     * Render previous/next controls
     *
     * @param string $id Carousel element ID
     * @return string
     */
    protected function renderControls(string $id): string
    {
        $templater = $this->templater();
        $target = '#' . $id;

        /** @var string $prevLabel */
        $prevLabel = $this->getConfig('prevLabel');
        /** @var string $nextLabel */
        $nextLabel = $this->getConfig('nextLabel');

        $prevAttrs = $this->mergeAttributes('controlPrev', []);
        $prevAttrs['data-bs-target'] = $target;

        $nextAttrs = $this->mergeAttributes('controlNext', []);
        $nextAttrs['data-bs-target'] = $target;

        $prevIconAttrs = $this->mergeAttributes('controlIcon', ['class' => 'carousel-control-prev-icon']);
        $nextIconAttrs = $this->mergeAttributes('controlIcon', ['class' => 'carousel-control-next-icon']);

        $prev = $templater->format('controlPrev', [
            'attrs' => $templater->formatAttributes($prevAttrs),
            'content' => $templater->format('controlIcon', [
                'attrs' => $templater->formatAttributes($prevIconAttrs),
            ]) . $templater->format('controlLabel', [
                'attrs' => $templater->formatAttributes($this->mergeAttributes('controlLabel', [])),
                'content' => $prevLabel,
            ]),
        ]);

        $next = $templater->format('controlNext', [
            'attrs' => $templater->formatAttributes($nextAttrs),
            'content' => $templater->format('controlIcon', [
                'attrs' => $templater->formatAttributes($nextIconAttrs),
            ]) . $templater->format('controlLabel', [
                'attrs' => $templater->formatAttributes($this->mergeAttributes('controlLabel', [])),
                'content' => $nextLabel,
            ]),
        ]);

        return $prev . $next;
    }

    /**
     * Index of the slide that should be active
     *
     * @return int
     */
    protected function getActiveItemIndex(): int
    {
        foreach ($this->items as $index => $item) {
            if (($item['options']['active'] ?? false) === true) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * Merge default attributes with provided attributes
     *
     * @param string $template The template name
     * @param array<string, mixed> $attrs The attributes to merge
     * @return array<string, mixed>
     */
    protected function mergeAttributes(string $template, array $attrs): array
    {
        $defaults = $this->_defaultAttributes[$template] ?? [];

        return $attrs + $defaults;
    }
}
