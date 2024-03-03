<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Themes\Example;

use Empaphy\Colorphul\ColorSchemeAppearance;
use Empaphy\Colorphul\Terminal\TerminalEmulatorColorScheme;
use Empaphy\Colorphul\ThemeGeneratorInterface;
use Symfony\Component\Yaml\Dumper;

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
class ExampleThemeGenerator implements ThemeGeneratorInterface
{
    public function __construct()
    {
        // Nothing to see here.
    }

    public function generate(string $name, TerminalEmulatorColorScheme $scheme, string $filePath): void
    {
        $template = new ExampleThemeTemplate($name, $scheme);

        ob_start();
        $template->render();
        $html = ob_get_clean();

        file_put_contents($filePath, $html);
    }
}
