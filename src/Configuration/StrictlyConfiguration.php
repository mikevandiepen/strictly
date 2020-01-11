<?php

namespace Mediadevs\Strictly\Configuration;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Finder\Finder;

/**
 * Class StrictlyConfiguration.
 *
 * @package Mediadevs\Strictly\Configuration
 */
final class StrictlyConfiguration
{
    /** @var array all the analysers which can be applied */
    private const STRICTLY_ANALYSER_OPTIONS = [
        // Global analysis scopes.
        'functional',
        'docblock',
        // Callable analysis scopes.
        'callable',
        'callable-functional',
        'callable-docblock',
        // Parameter analysis scopes.
        'parameter',
        'parameter-functional',
        'parameter-docblock',
        // Return analysis scopes.
        'return',
        'return-functional',
        'return-docblock',
        // Property analysis scopes.
        'property',
        'property-functional',
        'property-docblock',
        // Arrow function analysis scopes.
        'arrow-function',
        'arrow-function-functional',
        'arrow-function-docblock',
        'arrow-function-parameter-functional',
        'arrow-function-parameter-docblock',
        'arrow-function-return-functional',
        'arrow-function-return-docblock',
        // Closure analysis scopes.
        'closure',
        'closure-functional',
        'closure-docblock',
        'closure-parameter-functional',
        'closure-parameter-docblock',
        'closure-return-functional',
        'closure-return-docblock',
        // Function analysis scopes.
        'function',
        'function-functional',
        'function-docblock',
        'function-parameter-functional',
        'function-parameter-docblock',
        'function-return-functional',
        'function-return-docblock',
        // Magic method analysis scopes.
        'magic-method',
        'magic-method-functional',
        'magic-method-docblock',
        'magic-method-parameter-functional',
        'magic-method-parameter-docblock',
        'magic-method-return-functional',
        'magic-method-return-docblock',
        // Method analysis scopes.
        'method',
        'method-functional',
        'method-docblock',
        'method-parameter-functional',
        'method-parameter-docblock',
        'method-return-functional',
        'method-return-docblock',
    ];

    // The analysis configuration file.
    private const STRICTLY_CONFIGURATION_FILE = '.strictly.yml';

    /**
     * The path of the current working directory.
     *
     * @var string|null
     */
    private ?string $currentWorkingDirectory;

    /**
     * The finder which will be used to locate and parse the file.
     *
     * @var \Symfony\Component\Finder\Finder
     */
    private Finder $finder;

    /**
     * The configuration for the analyser.
     *
     * @var array
     */
    private array $configuration = array();

    /**
     * StrictlyConfiguration constructor.
     */
    public function __construct()
    {
        $this->finder = new Finder();
        $this->configuration = Yaml::parseFile(self::STRICTLY_CONFIGURATION_FILE);
    }

    /**
     * Collecting all the files which should be subject of analysis.
     *
     * @return \Iterator|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function getFiles(): \Traversable
    {
        // Configured directories.
        $includedDirectories = $this->getIncludedDirectories();
        $excludedDirectories = $this->getExcludedDirectories();

        // Whether the directories are configured.
        if (!isset($includedDirectories) && !isset($excludedDirectories)) {
            // Including all the configuration files.
            $this->finder->in($this->currentWorkingDirectory);
        } else {
            // Collecting the files which should be parsed from the configuration.
            if (isset($includedDirectories)) {
                $this->finder->in($includedDirectories);
            }
            // Collecting the files which shouldn't be parsed from the configuration.
            if (isset($excludedDirectories)) {
                $this->finder->exclude($excludedDirectories);
            }
        }
        // Including all the files which end on ".php".
        $this->finder->files()->name('*.php');

        return $this->finder->files()->getIterator();
    }

    /**
     * Collecting all analysers which can be applied in the analysis.
     *
     * @return array
     */
    public function getAnalysers(): array
    {
        // No enabled or disabled analysers have been configured, all analysers will be used.
        $analysers = self::STRICTLY_ANALYSER_OPTIONS;

        // The analyser configuration.
        $enabledAnalysers = $this->getEnabledAnalysers() ?? false;
        $disabledAnalysers = $this->getDisabledAnalysers() ?? false;

        // Whether the all ( * ) scope is set.
        if ($enabledAnalysers && in_array('*', $enabledAnalysers)) {
            $enabledAnalysers = self::STRICTLY_ANALYSER_OPTIONS;
        }
        if ($disabledAnalysers && in_array('*', $disabledAnalysers)) {
            $disabledAnalysers = self::STRICTLY_ANALYSER_OPTIONS;
        }

        // There are enabled and disabled analysers configured.
        // We will return the base analysers plus the enabled analysers minus the disabled analysers.
        if ($enabledAnalysers && $disabledAnalysers) {
            $analysers = self::STRICTLY_ANALYSER_OPTIONS;

            // Adding the enabled analysers to the base analysers.
            foreach ($enabledAnalysers as $enabledAnalyser) {
                $analysers[] = $enabledAnalyser;
            }

            // Removing the disabled analysers from the enabled and base analysers list.
            foreach ($disabledAnalysers as $disabledAnalyser) {
                $analysers = array_diff($analysers, [$disabledAnalyser]);
            }
        }

        // There are no enabled analysers, only disabled analysers.
        // The used analysers will be all the analysers minus the disabled ones.
        if ($disabledAnalysers && !$enabledAnalysers) {
            $analysers = array_diff( self::STRICTLY_ANALYSER_OPTIONS, $disabledAnalysers);
        }

        // There are no enabled analysers, only disabled analysers.
        // The used analysers will be all the analysers minus the disabled ones.
        if ($enabledAnalysers && !$disabledAnalysers) {
            $analysers = $enabledAnalysers;
        }

        return $analysers;
    }

    /**
     * Whether the configuration has enabled analysers.
     *
     * @return bool
     */
    private function hasEnabledAnalysers(): bool
    {
        return (bool) (isset($this->configuration['project']['analysers']['enabled'])) ? true : false;
    }

    /**
     * Collecting all the analysers which have been enabled.
     *
     * @return array|null
     */
    private function getEnabledAnalysers(): ?array
    {
        if ($this->hasEnabledAnalysers()) {
            return (array) $this->configuration['project']['analysers']['enabled'];
        }

        return null;
    }

    /**
     * Whether the configuration has disabled analysers..
     *
     * @return bool
     */
    private function hasDisabledAnalysers(): bool
    {
        return (bool) (isset($this->configuration['project']['analysers']['disabled'])) ? true : false;
    }

    /**
     * Collecting all the analysers which have been disabled.
     *
     * @return array|null
     */
    private function getDisabledAnalysers(): ?array
    {
        if ($this->hasDisabledAnalysers()) {
            return (array) $this->configuration['project']['analysers']['disabled'];
        }

        return null;
    }

    /**
     * Collecting all the names of the directories which should be analysed.
     *
     * @return array
     */
    private function getIncludedDirectories(): array
    {
        return $this->configuration['project']['directories']['include'] ?? array();
    }

    /**
     * Collecting all the names of the directories which shouldn't be analysed.
     *
     * @return array
     */
    private function getExcludedDirectories(): array
    {
        return $this->configuration['project']['directories']['exclude'] ?? array();
    }
}
