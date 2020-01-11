<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers;

use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;

/**
 * Class AnalyseDocblockHelper.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers
 */
final class AnalyseDocblockHelper
{
    /**
     * Extracting the type from the docblock.
     * An array of types will be returned, so if the type is a union type all the types will be returned.
     *
     * @param TagWithType $tag
     *
     * @return string[]
     */
    public function getTypeFromDocblock(TagWithType $tag): array
    {
        $type = [];

        if ($tag->getType() instanceof Compound) {
            foreach (explode('|' , $tag->getType()) as $tagType) {
                $type[] = $tagType;
            }
        } else {
            $type[] = $tag->getType();
        }

        return $type;
    }
}