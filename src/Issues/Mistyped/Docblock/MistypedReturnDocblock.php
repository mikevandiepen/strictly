<?php

namespace Mediadevs\StrictlyPHP\Issues\Mistyped\Docblock;

use Mediadevs\StrictlyPHP\Issues\Severity;
use Mediadevs\StrictlyPHP\Issues\DocblockInterface;
use Mediadevs\StrictlyPHP\Issues\Mistyped\MistypedInterface;

/**
 * Class MistypedReturnDocblock.
 *
 * @package Mediadevs\StrictlyPHP\Issues\Mistyped\Docblock
 */
final class MistypedReturnDocblock implements MistypedInterface, DocblockInterface
{
    /** @var string How the issue will be identified. */
    public const IDENTIFIER = 'mistyped-return-docblock';

    /** @var int How severe the current issue is. */
    public const SEVERITY = Severity::ALERT;

    /** @var string The message which the user will be prompted with. */
    public const MESSAGE_COMPACT = 'Incorrect return type in the docblock!';
}