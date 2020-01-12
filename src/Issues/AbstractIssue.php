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
     * The type(s) which the node should have.
     *
     * @var array
     */
    private array $type;

    /**
     * The parameter which the node should have.
     *
     * @var string|null
     */
    private ?string $parameter = null;

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

    /**
     * Setting the type(s) for the issue.
     *
     * @param array $type
     *
     * @return \Mediadevs\Strictly\Issues\AbstractIssue
     */
    public function setType(array $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Getting the type(s) for the issue.
     *
     * @return string
     */
    public function getType(): string
    {
        return implode('|', $this->type);
    }

    /**
     * Setting the parameter for the issue.
     *
     * @param string $parameter
     *
     * @return \Mediadevs\Strictly\Issues\AbstractIssue
     */
    public function setParameter(string $parameter): self
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Getting the parameter for the issue.
     *
     * @return string|null
     */
    public function getParameter(): ?string
    {
        return $this->parameter;
    }
}