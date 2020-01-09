<?php

namespace Mediadevs\Strictly\Issues;

/**
 * Class AbstractIssue.
 *
 * @package Mediadevs\Strictly\Issues
 */
abstract class AbstractIssue
{
    /**
     * The line where the issue is located.
     *
     * @var int
     */
    private int $line;

    /**
     * The name of the node which contains the issue.
     *
     * @var string
     */
    private string $name;

    /**
     * Setting the line for the issue.
     *
     * @param int $line
     *
     * @return \Mediadevs\Strictly\Issues\AbstractIssue
     */
    public function setLine(int $line): self
    {
        $this->line = $line;

        return $this;
    }

    /**
     * Getting the line for the issue.
     *
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * Setting the name of the node for the issue.
     *
     * @param string $name
     *
     * @return \Mediadevs\Strictly\Issues\AbstractIssue
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getting the name of the node for the issue.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}