<?php

namespace Mediadevs\Strictly\Command;

use Mediadevs\Strictly\Parser\File;
use Mediadevs\Strictly\Analyser\Director;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mediadevs\Strictly\Configuration\StrictlyConfiguration;

/**
 * Class StrictlyCommand.
 *
 * @package Mediadevs\Strictly\Command
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
        $configuration = new StrictlyConfiguration();

        $projectIssueCount = 0;
        foreach ($configuration->getFiles() as $configurationFile) {
            $file = new File($configurationFile);

            $analyserStrategy = new Director($file);
            $analyserStrategy->direct($configuration->getAnalysers());

            $output->writeln('Analysed file: [' . $file->fileName . '] - File size: ' . $file->fileSize);

            $table = new Table($output);
            $table->setHeaders(['Severity', 'Identifier', 'Line', 'Name', 'Issue']);

            // Parsing through the issues.
            $fileIssueCount = 0;
            foreach ($analyserStrategy->getIssues() as $issue) {
                $fileIssueCount++;

                $table->addRow([
                    $issue::SEVERITY,
                    $issue::IDENTIFIER,
                    $issue->getLine(),
                    $issue->getName(),
                    $issue::MESSAGE
                ]);
            }
            $projectIssueCount += $fileIssueCount;

            $output->writeln('[issues file]: ' . $fileIssueCount);
            $output->writeln('');
        }

        $output->writeln('[Issues total]: ' . $projectIssueCount);
    }
}
