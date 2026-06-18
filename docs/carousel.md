# CarouselHelper

Render Bootstrap 5 carousels with optional controls, indicators, crossfade transitions, autoplay, and per-slide captions. Calling `render()` clears the slide collection so you can build another carousel on the same helper instance.

## Basic Usage

```php
echo $this->Carousel
    ->add('<img src="/img/slide1.jpg" class="d-block w-100" alt="Slide 1">')
    ->add('<img src="/img/slide2.jpg" class="d-block w-100" alt="Slide 2">')
    ->add('<img src="/img/slide3.jpg" class="d-block w-100" alt="Slide 3">')
    ->render(['id' => 'hero-carousel']);
```

## With Indicators and Captions

```php
echo $this->Carousel
    ->add($this->Html->image('slide1.jpg', ['class' => 'd-block w-100', 'alt' => 'Slide 1']), [
        'caption' => [
            'title' => 'First slide label',
            'text' => 'Some representative placeholder content.',
        ],
    ])
    ->add($this->Html->image('slide2.jpg', ['class' => 'd-block w-100', 'alt' => 'Slide 2']), [
        'caption' => ['title' => 'Second slide', 'text' => 'More content.'],
    ])
    ->render([
        'id' => 'featured-carousel',
        'indicators' => true,
    ]);
```

## Crossfade and Autoplay

```php
echo $this->Carousel
    ->add('<img src="/img/slide1.jpg" class="d-block w-100" alt="Slide 1">', ['interval' => 10000])
    ->add('<img src="/img/slide2.jpg" class="d-block w-100" alt="Slide 2">', ['interval' => 2000])
    ->render([
        'id' => 'autoplay-carousel',
        'crossfade' => true,
        'autoplay' => 'carousel',
    ]);
```

## Methods

| Method | Description |
|--------|-------------|
| `add($content, $options)` | Add a slide with optional caption and attributes |
| `render($options)` | Render the carousel markup |

## Slide Options (`add`)

| Option | Type | Description |
|--------|------|-------------|
| `caption` | string\|array | Caption HTML string, or array with `title` and `text` keys |
| `captionAttrs` | array | HTML attributes for the caption element |
| `active` | bool | Mark slide as active (default: first slide) |
| `interval` | int | Per-slide autoplay delay in ms (`data-bs-interval`) |
| Other options | mixed | HTML attributes for the slide element |

## Render Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `id` | string | — | Carousel element ID (required when `controls` or `indicators` are enabled) |
| `controls` | bool | `true` | Show previous/next controls |
| `indicators` | bool | `false` | Show slide indicator buttons |
| `crossfade` | bool | `false` | Use fade transition (adds `carousel-fade` class) |
| `autoplay` | bool\|string | `false` | `false`, `true` (after first interaction), or `'carousel'` (on page load) |
| `carouselAttrs` | array | `[]` | HTML attributes for the carousel element |
| `innerAttrs` | array | `[]` | HTML attributes for the carousel-inner element |
| `captionAttrs` | array | `[]` | Default HTML attributes for caption elements |

## Templates

| Template | Default HTML |
|----------|--------------|
| `carousel` | `<div{{attrs}}>{{content}}</div>` |
| `inner` | `<div{{attrs}}>{{content}}</div>` |
| `item` | `<div{{attrs}}>{{content}}</div>` |
| `caption` | `<div{{attrs}}>{{content}}</div>` |
| `indicators` | `<div{{attrs}}>{{content}}</div>` |
| `indicator` | `<button{{attrs}}></button>` |
| `controlPrev` | `<button{{attrs}}>{{content}}</button>` |
| `controlNext` | `<button{{attrs}}>{{content}}</button>` |
| `controlIcon` | `<span{{attrs}}></span>` |
| `controlLabel` | `<span{{attrs}}>{{content}}</span>` |

## Default Classes

| Element | Default Classes |
|---------|-----------------|
| carousel | `carousel slide` |
| inner | `carousel-inner` |
| item | `carousel-item` |
| caption | `carousel-caption d-none d-md-block` |
| indicators | `carousel-indicators` |
| controlPrev | `carousel-control-prev` |
| controlNext | `carousel-control-next` |

## Accessibility

- Controls use `<button>` elements with `carousel-control-prev-icon` / `carousel-control-next-icon` and visually hidden labels (configurable via `prevLabel` / `nextLabel` config).
- Indicators include `aria-label="Slide N"`; the active indicator has `aria-current="true"`.
- A unique `id` is required when controls or indicators are enabled so `data-bs-target` references resolve correctly.

Bootstrap 5 handles slide transitions via `data-bs-slide`, `data-bs-slide-to`, and `data-bs-ride` attributes. No additional JavaScript is required when using autoplay with `data-bs-ride="carousel"`.

See also: [Template customization](template-customization.md)
