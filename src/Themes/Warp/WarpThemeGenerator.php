<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Themes\Warp;

use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorPallet;
use Empaphy\Colorphul\ThemeGeneratorInterface;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Yaml;

/**
 * @phpstan-type WarpThemeArray array{
 *     details: 'darker',
 *     accent: string,
 *     background: string,
 *     foreground: string,
 *     terminal_colors: array{
 *         normal: array{
 *             black: string,
 *             red: string,
 *             green: string,
 *             yellow: string,
 *             blue: string,
 *             magenta: string,
 *             cyan: string,
 *             white: string,
 *         },
 *         bright: array{
 *             black: string,
 *             red: string,
 *             green: string,
 *             yellow: string,
 *             blue: string,
 *             magenta: string,
 *             cyan: string,
 *             white: string,
 *         },
 *     },
 * }
 */
readonly class WarpThemeGenerator implements ThemeGeneratorInterface
{
    public function __construct(private readonly Dumper $yamlDumper)
    {
        // Nothing to see here.
    }

    public function generate(TerminalEmulatorColorPallet $scheme, string $filePath): void
    {
        $theme = [
            'details'    => $scheme->appearance === ColorSchemeAppearance::Dark ? 'darker' : 'lighter',
            'accent'     => (string) $scheme->accent->toHexRgb(),
            'background' => (string) $scheme->background->toHexRgb(),
            'foreground' => (string) $scheme->foreground->toHexRgb(),
            'terminal_colors' => [
                'normal' => [
                    'black'   => (string) $scheme->colors->normal->black->toHexRgb(),
                    'red'     => (string) $scheme->colors->normal->red->toHexRgb(),
                    'green'   => (string) $scheme->colors->normal->green->toHexRgb(),
                    'yellow'  => (string) $scheme->colors->normal->yellow->toHexRgb(),
                    'blue'    => (string) $scheme->colors->normal->blue->toHexRgb(),
                    'magenta' => (string) $scheme->colors->normal->magenta->toHexRgb(),
                    'cyan'    => (string) $scheme->colors->normal->cyan->toHexRgb(),
                    'white'   => (string) $scheme->colors->normal->white->toHexRgb(),
                ],
                'bright' => [
                    'black'   => (string) $scheme->colors->bright->black->toHexRgb(),
                    'red'     => (string) $scheme->colors->bright->red->toHexRgb(),
                    'green'   => (string) $scheme->colors->bright->green->toHexRgb(),
                    'yellow'  => (string) $scheme->colors->bright->yellow->toHexRgb(),
                    'blue'    => (string) $scheme->colors->bright->blue->toHexRgb(),
                    'magenta' => (string) $scheme->colors->bright->magenta->toHexRgb(),
                    'cyan'    => (string) $scheme->colors->bright->cyan->toHexRgb(),
                    'white'   => (string) $scheme->colors->bright->white->toHexRgb(),
                ],
            ],
        ];

        $yaml = $this->yamlDumper->dump($theme, 4);

        file_put_contents($filePath, $yaml);
    }
}
