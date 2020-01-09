<?php

namespace Mediadevs\StrictlyPHP\Issues\Untyped\Docblock;

use Mediadevs\StrictlyPHP\Issues\Severity;
use Mediadevs\StrictlyPHP\Issues\DocblockInterface;
use Mediadevs\StrictlyPHP\Issues\Untyped\UntypedInterface;

/**
 * Class UntypedPropertyDocblock.
 *
 * @package Mediadevs\StrictlyPHP\Issues\Untyped\Docblock
 */
final class UntypedPropertyDocblock implements UntypedInterface, DocblockInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'untyped-property-docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE_COMPACT = 'Undeclared property type in the docblock!';
}