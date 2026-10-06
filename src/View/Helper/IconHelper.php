<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Icon Helper
 *
 * Renders icon-font markup from a configurable namespace and prefix.
 * Defaults match Bootstrap Icons. Per-call options can use Font Awesome,
 * Tabler, or another set on the same page.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 */
class IconHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default config for the helper.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'namespace' => 'bi',
        'prefix' => 'bi',
        'tag' => 'i',
        'templates' => [
            'icon' => '<{{tag}}{{attrs}}></{{tag}}>',
        ],
    ];

    /**
     * Render an icon.
     *
     * Options:
     * - `namespace`: Set class (`bi`, `fa-solid`, `fas`, `ti`). Overrides the helper default.
     *   An empty string omits the set class.
     * - `prefix`: Class prefix. The icon class is `{prefix}-{name}`. Overrides the helper default.
     *   An empty string uses the icon name as the class.
     * - `size`: Size token. Adds `{prefix}-{size}` when `prefix` is not empty.
     * - `tag`: HTML tag. Default `i`.
     * - `class`: Extra classes appended to the generated icon classes.
     * - Other keys are HTML attributes.
     *
     * @param string $name Icon name (for example `house` or `user`).
     * @param array<string, mixed> $options Options for this icon.
     * @return string
     */
    public function icon(string $name, array $options = []): string
    {
        $templater = $this->templater();

        $namespace = $this->configuredString($options, 'namespace');
        $prefix = $this->configuredString($options, 'prefix');
        $size = $this->optionString($options, 'size');
        $tag = $this->optionString($options, 'tag');
        if ($tag === '') {
            $tag = $this->configString('tag');
            if ($tag === '') {
                $tag = 'i';
            }
        }
        $extraClass = $this->optionString($options, 'class');

        unset(
            $options['namespace'],
            $options['prefix'],
            $options['size'],
            $options['tag'],
            $options['class'],
        );

        $options['class'] = $this->iconClasses($name, $namespace, $prefix, $size, $extraClass);

        return $templater->format('icon', [
            'tag' => $tag,
            'attrs' => $templater->formatAttributes($options),
        ]);
    }

    /**
     * Build the icon class attribute.
     *
     * @param string $name Icon name.
     * @param string $namespace Set class, or an empty string to omit it.
     * @param string $prefix Class prefix, or an empty string to use the icon name as the class.
     * @param string $size Size token, or an empty string to omit the size class.
     * @param string $extraClass Additional classes supplied by the caller.
     * @return string
     */
    protected function iconClasses(
        string $name,
        string $namespace,
        string $prefix,
        string $size,
        string $extraClass,
    ): string {
        $classes = [];
        if ($namespace !== '') {
            $classes[] = $namespace;
        }
        if ($prefix !== '') {
            $classes[] = $prefix . '-' . $name;
            if ($size !== '') {
                $classes[] = $prefix . '-' . $size;
            }
        } else {
            $classes[] = $name;
        }
        if ($extraClass !== '') {
            $classes[] = $extraClass;
        }

        return implode(' ', $classes);
    }

    /**
     * Read a string option when set, otherwise the helper config.
     *
     * An empty string in `$options` is kept so a call can clear the default.
     *
     * @param array<string, mixed> $options Options array.
     * @param string $key Option name.
     * @return string
     */
    protected function configuredString(array $options, string $key): string
    {
        if (array_key_exists($key, $options)) {
            $value = $options[$key];
            if (!is_string($value)) {
                return '';
            }

            return trim($value);
        }

        return $this->configString($key);
    }

    /**
     * Read a string from helper config.
     *
     * @param string $key Config key.
     * @return string
     */
    protected function configString(string $key): string
    {
        $value = $this->getConfig($key);
        if (!is_string($value)) {
            return '';
        }

        return trim($value);
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
}
