<?php

declare(strict_types=1);

namespace Empaphy\Colorphul\Command;

use Empaphy\Colorphul\Schemes\ColorphulScheme;
use Empaphy\Colorphul\Themes\Warp\WarpThemeGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates the theme files.
 */
#[AsCommand(
    name:        'themes:generate',
    description: 'Generates color theme files.',
    hidden:      false,
)]
class GenerateThemesCommand extends Command
{
    public function __construct(
        private readonly WarpThemeGenerator $warpThemeGenerator,
        private readonly ColorphulScheme    $colorphulScheme,
        string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class as a concrete class. In this case, instead of defining
     * the execute() method, you set the code to execute by passing a Closure to the {@see setCode()} method.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface    $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int `0` if everything went fine, or an exit code.
     *
     * @throws \Symfony\Component\Console\Exception\LogicException When this abstract method is not implemented.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->warpThemeGenerator->generate($this->colorphulScheme, dirname(__DIR__, 2) . '/themes/warp/colorphul_apca.yaml');

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
