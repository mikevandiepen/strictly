<?php

namespace Mediadevs\Strictly\Issues\Untyped\Docblock;

use Mediadevs\Strictly\Issues\Severity;
use Mediadevs\Strictly\Issues\AbstractIssue;
use Mediadevs\Strictly\Issues\Contracts\IssueInterface;
use Mediadevs\Strictly\Issues\Contracts\UntypedInterface;
use Mediadevs\Strictly\Issues\Contracts\DocblockInterface;

/**
 * Class UntypedPropertyDocblock.
 *
 * @package Mediadevs\Strictly\Issues\Untyped\Docblock
 */
final class UntypedPropertyDocblock extends AbstractIssue implements IssueInterface, UntypedInterface, DocblockInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-property-docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with when the print flag is "abstract". */
    public const ABSTRACT_MESSAGE = 'Undeclared property type in the docblock';

    /** @var string The message which the user will be prompted with when the print flag is "simple". */
    public const SIMPLE_MESSAGE = 'The docblock of "%s" has an undeclared property type at line:%d, the property type should be "%s"';
}