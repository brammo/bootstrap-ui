<?php
declare(strict_types=1);

namespace Brammo\BootstrapUI\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Table Helper
 */
class TableHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * Default config for the helper.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'templates' => [
            'wrapper' => '<div{{attrs}}>{{content}}</div>',
            'table' => '<table{{attrs}}>{{content}}</table>',
            'header' => '<thead{{attrs}}>{{content}}</thead>',
            'body' => '<tbody{{attrs}}>{{content}}</tbody>',
            'row' => '<tr{{attrs}}>{{content}}</tr>',
            'headerCell' => '<th{{attrs}}>{{content}}</th>',
            'bodyCell' => '<td{{attrs}}>{{content}}</td>',
        ],
    ];

    /**
     * Default attributes for the templates
     *
     * @var array<string, array<string, string>>
     */
    protected array $_defaultAttributes = [
        'wrapper' => [
            'class' => 'table-responsive',
        ],
        'table' => [
            'class' => 'table',
        ],
    ];

    /**
     * The table header.
     *
     * @var array<int, mixed>
     */
    protected array $header = [];

    /**
     * The table header options
     *
     * @var array<string, mixed>
     */
    protected array $headerOptions = [];

    /**
     * The table body
     *
     * @var array<int, array{data: array<int, mixed>, options: array<string, mixed>}>
     */
    protected array $body = [];

    /**
     * The table body options
     *
     * @var array<string, mixed>
     */
    protected array $bodyOptions = [];

    /**
     * Sets up the table header
     *
     * Options:
     * - `header`: Header data
     * - `headerOptions`: HTML attributes for the header element
     *
     * @param array<int, mixed> $data Header data
     * @param array<string, mixed> $options HTML attributes.
     * @return void
     */
    public function header(array $data, array $options = []): void
    {
        $this->header = $data;
        $this->headerOptions = $options;
    }

    /**
     * Adds a table row
     *
     * @param array<int, mixed> $data Row data
     * @param array<string, mixed> $options HTML attributes for the row element
     * @return void
     */
    public function row(array $data, array $options = []): void
    {
        $this->body[] = ['data' => $data, 'options' => $options];
    }

    /**
     * Sets the body options
     *
     * @param array<string, mixed> $options HTML attributes for the body element
     * @return void
     */
    public function body(array $options): void
    {
        $this->bodyOptions = $options;
    }

    /**
     * Renders the table
     *
     * Options:
     * - `wrapper`: HTML attributes for the wrapper div
     * - `table`: HTML attributes for the table element
     * - `body`: HTML attributes for the tbody element
     *
     * @param array<string, mixed> $options Options
     * @return string
     */
    public function render(array $options = []): string
    {
        $options += $this->_defaultAttributes;

        if (isset($options['body'])) {
            $this->bodyOptions = $options['body'] + $this->bodyOptions;
        }

        $templater = $this->templater();

        return $templater->format('wrapper', [
            'attrs' => $templater->formatAttributes($options['wrapper']),
            'content' => $templater->format('table', [
                'attrs' => $templater->formatAttributes($options['table']),
                'content' => $this->renderHeader() . $this->renderBody(),
            ]),
        ]);
    }

    /**
     * Renders table header
     *
     * @return string
     */
    private function renderHeader(): string
    {
        if (empty($this->header)) {
            return '';
        }

        $templater = $this->templater();

        $cells = [];
        foreach ($this->header as $arg) {
            if (!is_array($arg)) {
                $content = $arg;
                $attrs = [];
            } elseif (isset($arg[0], $arg[1])) {
                $content = $arg[0];
                $attrs = $arg[1];
            } else {
                $content = key($arg);
                $attrs = current($arg);
            }
            $cells[] = $templater->format('headerCell', [
                'attrs' => $templater->formatAttributes($attrs),
                'content' => $content,
            ]);
        }

        $this->header = [];

        return $templater->format('header', [
            'content' => $templater->format('row', ['content' => implode(' ', $cells)]),
        ]);
    }

    /**
     * Renders table body
     *
     * @return string
     */
    private function renderBody(): string
    {
        if (empty($this->body)) {
            return '';
        }

        $templater = $this->templater();

        $rows = [];
        foreach ($this->body as $row) {
            $cells = [];
            foreach ($row['data'] as $cell) {
                $cellOptions = [];

                if (is_array($cell)) {
                    $cellOptions = $cell[1];
                    $cell = $cell[0];
                }

                $cells[] = $templater->format('bodyCell', [
                    'attrs' => $templater->formatAttributes($cellOptions),
                    'content' => $cell,
                ]);
            }

            $rows[] = $templater->format('row', [
                'attrs' => $templater->formatAttributes($row['options']),
                'content' => implode(' ', $cells),
            ]);
        }

        $this->body = [];
        $bodyOptions = $this->bodyOptions;
        $this->bodyOptions = [];

        return $templater->format('body', [
            'attrs' => $templater->formatAttributes($bodyOptions),
            'content' => implode(' ', $rows),
        ]);
    }
}
