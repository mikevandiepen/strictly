<?php

namespace Mediadevs\StrictlyPHP\Command;

use PhpParser\Error;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StrictlyCommand.
 *
 * @package Mediadevs\StrictlyPHP\Command
 */
final class StrictlyCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'strictly';

    /**
     * Configuring the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Analysing the strictness of your project.');
        $this->setHelp('This command allows you to analyse your project and assert the strictness of your code.');
    }

    /**
     * Executing the strictly command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        // creates a new progress bar (50 units)
        $progressBar = new ProgressBar($output, 50);

        // starts and displays the progress bar
        $progressBar->start();

        $i = 0;
        while ($i++ < 50) {
            // ... do some work

            // advances the progress bar 1 unit
            $progressBar->advance();

            // you can also advance the progress bar by more than 1 unit
            // $progressBar->advance(3);
        }

        // ensures that the progress bar is at 100%
        $progressBar->finish();
    }
}
