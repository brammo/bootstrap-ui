<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Badge Helper
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 */
class BadgeHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default config for the helper.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'templates' => [
            'badge' => '<{{tag}}{{attrs}}>{{content}}</{{tag}}>',
            'button' => '<button{{attrs}}>{{content}}</button>',
            'visuallyHidden' => '<span{{attrs}}>{{content}}</span>',
        ],
    ];

    /**
     * Default attributes for the templates
     *
     * @var array<string, array<string, string>>
     */
    protected array $_defaultAttributes = [
        'button' => [
            'type' => 'button',
            'class' => 'btn btn-primary',
        ],
        'visuallyHidden' => [
            'class' => 'visually-hidden',
        ],
    ];

    /**
     * Render a badge.
     *
     * Options:
     * - `color`: Theme name (`primary`, `secondary`, `success`, `danger`, `warning`, `info`, `light`, `dark`).
     *   Adds `text-bg-{color}`, or `bg-{color}` when `positioned` or `indicator` is set.
     *   When omitted and `class` is also omitted, defaults to `secondary`.
     * - `class`: Custom color/background classes, or extra utilities (padding, margin).
     *   With `color`, the theme class is kept and `class` is appended.
     *   Without `color`, no default color class is added.
     * - `pill`: Add `rounded-pill`. Defaults to false, or true when `positioned` is set and `indicator` is not.
     * - `tag`: HTML tag name. Default `span`.
     * - `positioned`: Corner badge (`position-absolute top-0 start-100 translate-middle`).
     * - `indicator`: Notification dot. Visible text is omitted. Takes precedence over `positioned`.
     * - `visuallyHidden`: Screen-reader text rendered inside the badge.
     * - Other keys are HTML attributes for the badge element.
     *
     * Content is not escaped.
     *
     * @param string $text Badge text. Omitted when `indicator` is true.
     * @param array<string, mixed> $options Options for rendering the badge.
     * @return string
     */
    public function render(string $text, array $options = []): string
    {
        $templater = $this->templater();

        $tag = $this->optionString($options, 'tag');
        if ($tag === '') {
            $tag = 'span';
        }

        $visuallyHidden = $options['visuallyHidden'] ?? null;
        $indicator = $this->optionBool($options, 'indicator');

        $attrs = $this->badgeAttributes($options);
        $content = $indicator ? '' : $text;
        if (is_string($visuallyHidden) && $visuallyHidden !== '') {
            $hiddenAttrs = $this->mergeAttributes('visuallyHidden', []);
            $content .= $templater->format('visuallyHidden', [
                'attrs' => $templater->formatAttributes($hiddenAttrs),
                'content' => $visuallyHidden,
            ]);
        }

        return $templater->format('badge', [
            'tag' => $tag,
            'attrs' => $templater->formatAttributes($attrs),
            'content' => $content,
        ]);
    }

    /**
     * Render a primary badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function primary(string $text, array $options = []): string
    {
        return $this->renderColor('primary', $text, $options);
    }

    /**
     * Render a secondary badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function secondary(string $text, array $options = []): string
    {
        return $this->renderColor('secondary', $text, $options);
    }

    /**
     * Render a success badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function success(string $text, array $options = []): string
    {
        return $this->renderColor('success', $text, $options);
    }

    /**
     * Render a danger badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function danger(string $text, array $options = []): string
    {
        return $this->renderColor('danger', $text, $options);
    }

    /**
     * Render a warning badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function warning(string $text, array $options = []): string
    {
        return $this->renderColor('warning', $text, $options);
    }

    /**
     * Render an info badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function info(string $text, array $options = []): string
    {
        return $this->renderColor('info', $text, $options);
    }

    /**
     * Render a light badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function light(string $text, array $options = []): string
    {
        return $this->renderColor('light', $text, $options);
    }

    /**
     * Render a dark badge.
     *
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    public function dark(string $text, array $options = []): string
    {
        return $this->renderColor('dark', $text, $options);
    }

    /**
     * Render a button with a badge.
     *
     * Options other than `buttonAttrs` are badge options passed to render(), including `color`,
     * `class`, `pill`, `tag`, `visuallyHidden`, `positioned`, `indicator`, and `badgeAttrs`.
     * `class` follows the same color rules as render().
     *
     * - `buttonAttrs`: HTML attributes for the button. Default `type` is `button` and default
     *   `class` is `btn btn-primary`. A provided `class` replaces `btn btn-primary`.
     *   `positioned` or `indicator` also adds `position-relative`.
     * - `badgeAttrs`: Extra HTML attributes for the badge. Top-level badge options win.
     *
     * @param string $label Button label.
     * @param string $text Badge text.
     * @param array<string, mixed> $options Button and badge options.
     * @return string
     */
    public function button(string $label, string $text, array $options = []): string
    {
        $templater = $this->templater();

        $positioned = $this->optionBool($options, 'positioned');
        $indicator = $this->optionBool($options, 'indicator');

        /** @var array<string, mixed> $buttonAttrs */
        $buttonAttrs = is_array($options['buttonAttrs'] ?? null) ? $options['buttonAttrs'] : [];
        unset($options['buttonAttrs']);

        /** @var array<string, mixed> $badgeAttrs */
        $badgeAttrs = is_array($options['badgeAttrs'] ?? null) ? $options['badgeAttrs'] : [];
        unset($options['badgeAttrs']);

        $badge = $this->render($text, $options + $badgeAttrs);

        $buttonAttributes = $this->mergeAttributes('button', $buttonAttrs);
        $buttonClass = $buttonAttributes['class'] ?? '';
        if (!is_string($buttonClass)) {
            $buttonClass = '';
        }
        if ($positioned || $indicator) {
            $parts = $buttonClass === '' ? [] : explode(' ', $buttonClass);
            if (!in_array('position-relative', $parts, true)) {
                $parts[] = 'position-relative';
            }
            $buttonClass = implode(' ', $parts);
        }
        $buttonAttributes['class'] = $buttonClass;

        $content = $label === '' ? $badge : $label . ' ' . $badge;

        return $templater->format('button', [
            'attrs' => $templater->formatAttributes($buttonAttributes),
            'content' => $content,
        ]);
    }

    /**
     * Render a badge with a theme color.
     *
     * @param string $color Theme name.
     * @param string $text Badge text.
     * @param array<string, mixed> $options Options passed to render().
     * @return string
     */
    protected function renderColor(string $color, string $text, array $options): string
    {
        $options['color'] = $color;

        return $this->render($text, $options);
    }

    /**
     * Build badge element attributes, including structural and color classes.
     *
     * @param array<string, mixed> $options Render options.
     * @return array<string, mixed>
     */
    protected function badgeAttributes(array $options): array
    {
        $colorName = $this->optionString($options, 'color');
        $extraClass = $this->optionString($options, 'class');
        $positioned = $this->optionBool($options, 'positioned');
        $indicator = $this->optionBool($options, 'indicator');

        if (array_key_exists('pill', $options)) {
            $pill = (bool)$options['pill'];
        } else {
            $pill = $positioned && !$indicator;
        }

        if ($colorName === '' && $extraClass === '') {
            $colorName = 'secondary';
        }

        unset(
            $options['color'],
            $options['class'],
            $options['pill'],
            $options['tag'],
            $options['positioned'],
            $options['indicator'],
            $options['visuallyHidden'],
            $options['buttonAttrs'],
            $options['badgeAttrs'],
        );

        $attrs = $this->mergeAttributes('badge', $options);
        $attrs['class'] = $this->badgeClasses($indicator, $positioned, $pill, $colorName, $extraClass);

        return $attrs;
    }

    /**
     * Build the badge class attribute.
     *
     * @param bool $indicator Whether this is an indicator dot.
     * @param bool $positioned Whether this is a positioned badge.
     * @param bool $pill Whether to add `rounded-pill`.
     * @param string $colorName Theme name, or an empty string when no color class should be added.
     * @param string $extraClass Additional classes supplied by the caller.
     * @return string
     */
    protected function badgeClasses(
        bool $indicator,
        bool $positioned,
        bool $pill,
        string $colorName,
        string $extraClass,
    ): string {
        if ($indicator) {
            $classes = [
                'position-absolute',
                'top-0',
                'start-100',
                'translate-middle',
                'p-2',
            ];
            if ($colorName !== '') {
                $classes[] = 'bg-' . $colorName;
            }
            $classes[] = 'border';
            $classes[] = 'border-light';
            $classes[] = 'rounded-circle';
        } elseif ($positioned) {
            $classes = [
                'position-absolute',
                'top-0',
                'start-100',
                'translate-middle',
                'badge',
            ];
            if ($pill) {
                $classes[] = 'rounded-pill';
            }
            if ($colorName !== '') {
                $classes[] = 'bg-' . $colorName;
            }
        } else {
            $classes = ['badge'];
            if ($colorName !== '') {
                $classes[] = 'text-bg-' . $colorName;
            }
            if ($pill) {
                $classes[] = 'rounded-pill';
            }
        }

        if ($extraClass !== '') {
            $classes[] = $extraClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Read a string option, trimmed.
     *
     * @param array<string, mixed> $options Options array.
     * @param string $key Option name.
     * @return string
     */
    protected function optionString(array $options, string $key): string
    {
        $value = $options[$key] ?? null;
        if (!is_string($value)) {
            return '';
        }

        return trim($value);
    }

    /**
     * Read a boolean option.
     *
     * @param array<string, mixed> $options Options array.
     * @param string $key Option name.
     * @return bool
     */
    protected function optionBool(array $options, string $key): bool
    {
        return (bool)($options[$key] ?? false);
    }

    /**
     * Merge default attributes with provided attributes.
     *
     * @param string $template The template name.
     * @param array<string, mixed> $attrs The attributes to merge.
     * @return array<string, mixed>
     */
    protected function mergeAttributes(string $template, array $attrs): array
    {
        $defaults = $this->_defaultAttributes[$template] ?? [];

        return $attrs + $defaults;
    }
}
