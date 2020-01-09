<?php

namespace Mediadevs\StrictlyPHP\Issues\Untyped\Docblock;

use Mediadevs\StrictlyPHP\Issues\Severity;
use Mediadevs\StrictlyPHP\Issues\AbstractIssue;
use Mediadevs\StrictlyPHP\Issues\Contracts\IssueInterface;
use Mediadevs\StrictlyPHP\Issues\Contracts\UntypedInterface;
use Mediadevs\StrictlyPHP\Issues\Contracts\DocblockInterface;

/**
 * Class UntypedReturnDocblock.
 *
 * @package Mediadevs\StrictlyPHP\Issues\Untyped\Docblock
 */
final class UntypedReturnDocblock extends AbstractIssue implements IssueInterface, UntypedInterface, DocblockInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-return-docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE = 'Undeclared return type in the docblock!';
}