<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Themes\Warp;

use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorScheme;
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
class WarpThemeGenerator implements ThemeGeneratorInterface
{
    public function __construct(private readonly Dumper $yamlDumper)
    {
        // Nothing to see here.
    }

    public function generate(string $name, TerminalEmulatorColorScheme $scheme, string $filePath): void
    {
        $theme = [
            'details'    => $scheme->appearance === ColorSchemeAppearance::Dark ? 'darker' : 'lighter',
            'background' => (string) $scheme->background->toHexRgb(),
            'foreground' => (string) $scheme->foreground->toHexRgb(),
            'terminal_colors' => [
                'normal' => [
                    'black'   => (string) $scheme->colorSets->normal->black->toHexRgb(),
                    'red'     => (string) $scheme->colorSets->normal->red->toHexRgb(),
                    'green'   => (string) $scheme->colorSets->normal->green->toHexRgb(),
                    'yellow'  => (string) $scheme->colorSets->normal->yellow->toHexRgb(),
                    'blue'    => (string) $scheme->colorSets->normal->blue->toHexRgb(),
                    'magenta' => (string) $scheme->colorSets->normal->magenta->toHexRgb(),
                    'cyan'    => (string) $scheme->colorSets->normal->cyan->toHexRgb(),
                    'white'   => (string) $scheme->colorSets->normal->white->toHexRgb(),
                ],
                'bright' => [
                    'black'   => (string) $scheme->colorSets->bright->black->toHexRgb(),
                    'red'     => (string) $scheme->colorSets->bright->red->toHexRgb(),
                    'green'   => (string) $scheme->colorSets->bright->green->toHexRgb(),
                    'yellow'  => (string) $scheme->colorSets->bright->yellow->toHexRgb(),
                    'blue'    => (string) $scheme->colorSets->bright->blue->toHexRgb(),
                    'magenta' => (string) $scheme->colorSets->bright->magenta->toHexRgb(),
                    'cyan'    => (string) $scheme->colorSets->bright->cyan->toHexRgb(),
                    'white'   => (string) $scheme->colorSets->bright->white->toHexRgb(),
                ],
            ],
        ];

        if ($scheme->accent !== null) {
            $theme['accent'] = (string) $scheme->accent->toHexRgb();
        }

        $yaml = $this->yamlDumper->dump($theme, 4);

        file_put_contents($filePath, $yaml);
    }
}
