<?php

namespace Mediadevs\Strictly\Command;

use Mediadevs\Strictly\Parser\File;
use Mediadevs\Strictly\Analyser\Director;
use Symfony\Component\Console\Helper\Table;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
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
        $this->addOption('format', null, InputOption::VALUE_REQUIRED, 'format can be either detailed or abstract', 'simple');
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

            // Parsing through the issues.
            $table = new Table($output);
            $fileIssueCount = 0;
            foreach ($analyserStrategy->getIssues() as $issue) {
                $fileIssueCount++;

                switch ($issue::SEVERITY) {
                    case 1:
                        $severity = 'info';
                        break;
                    case 2:
                        $severity = 'alert';
                        break;
                    case 3:
                        $severity = 'warning';
                        break;
                    default:
                        $severity = 'info';
                }

                switch ($input->getOption('format')) {
                    case 'abstract':
                        $table = $this->abstractIssue($table, $issue, $severity);
                        break;
                    case 'simple':
                        $table = $this->detailedIssue($table, $issue, $severity);
                        break;
                    default:
                        $output->writeln(sprintf('<error>%s is not a valid format choice</error>', $input->getOption('format')));
                }
            }
            $projectIssueCount += $fileIssueCount;
            $table->render();

            if ($fileIssueCount > 0) {
                $output->writeln('<comment>[issues file]: ' . $fileIssueCount . '</comment>');
            } else {
                $output->writeln('<info>[No issues found]</info>');
            }
            $output->writeln('');
        }

        $output->writeln('<comment>[Issues total]: ' . $projectIssueCount . '</comment>');
    }

    /**
     * Abstract issue format
     *
     * @param \Symfony\Component\Console\Helper\Table  $table
     * @param \Mediadevs\Strictly\Issues\AbstractIssue $issue
     * @param string                                   $severity
     *
     * @return \Symfony\Component\Console\Helper\Table
     */
    private function abstractIssue(Table $table, AbstractIssue $issue, string $severity): Table
    {
        $table->setHeaders(['Line', 'Location', 'Severity', 'Identifier', 'Name', 'Issue', 'Suggested type']);

        $table->addRow([
            $issue->getLine(),
            $issue::LOCATION,
            $severity,
            $issue::IDENTIFIER,
            $issue->getName(),
            $issue::ABSTRACT_MESSAGE,
            $issue->getType(),
        ]);

        return $table;
    }

    /**
     * Detailed issue format
     *
     * @param \Symfony\Component\Console\Helper\Table  $table
     * @param \Mediadevs\Strictly\Issues\AbstractIssue $issue
     * @param string                                   $severity
     *
     * @return \Symfony\Component\Console\Helper\Table
     */
    private function detailedIssue(Table $table, AbstractIssue $issue, string $severity): Table
    {
        $table->setHeaders(['Severity', 'Issue']);

        // Generating the message configuration based upon the issue.
        switch ($issue::IDENTIFIER) {
            case 'mistyped-return':
            case 'untyped-property-docblock':
            case 'untyped-parameter-docblock':
            case 'untyped-return-docblock':
            case 'untyped-property-functional':
            case 'untyped-parameter-functional':
            case 'untyped-return-functional':
            case 'mistyped-property':
                $message = sprintf(
                    $issue::SIMPLE_MESSAGE,
                    $issue->getName(),
                    $issue->getLine(),
                    $issue->getType()
                );
                break;
            case 'mistyped-parameter':
                $message = sprintf(
                    $issue::SIMPLE_MESSAGE,
                    $issue->getParameter(),
                    $issue->getName(),
                    $issue->getLine(),
                    $issue->getType()
                );
                break;
            default:
                $message = 'Issue message not found.';
        }

        $table->addRow([$severity, $message]);

        return $table;
    }
}
