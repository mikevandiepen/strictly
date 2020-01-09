<?php

namespace Mediadevs\StrictlyPHP\Issues\Untyped\Docblock;

use Mediadevs\StrictlyPHP\Issues\Severity;
use Mediadevs\StrictlyPHP\Issues\AbstractIssue;
use Mediadevs\StrictlyPHP\Issues\Contracts\IssueInterface;
use Mediadevs\StrictlyPHP\Issues\Contracts\UntypedInterface;
use Mediadevs\StrictlyPHP\Issues\Contracts\DocblockInterface;

/**
 * Class UntypedParameterDocblock.
 *
 * @package Mediadevs\StrictlyPHP\Issues\Untyped\Docblock
 */
final class UntypedParameterDocblock extends AbstractIssue implements IssueInterface, UntypedInterface, DocblockInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-parameter-docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Undeclared parameter type in the docblock!';
}