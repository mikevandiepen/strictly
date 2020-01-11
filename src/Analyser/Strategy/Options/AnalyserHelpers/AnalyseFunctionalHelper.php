<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers;

use PhpParser\Node;

/**
 * Class AnalyseFunctionalHelper.
 *
 * @package Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers
 */
final class AnalyseFunctionalHelper
{
    /**
     * Extracting the type from the node.
     * An array of types will be returned, so if the type is a union type all the types will be returned.
     *
     * @param Node\Identifier|Node\Name|Node\NullableType|Node\UnionType|null $node
     *
     * @return string[]
     */
    public function getTypeFromNode($node): array
    {
        $types = [];

        if ($node === null) {
            return $types;
        }

        if ($node instanceof Node\Param) {
            $types[] = $this->getTypeFromNode($node->type);
        }

        if (isset($node->type)) {
            if (isset($node->type) && ($node->type === null || in_array($node->type, ['NULL', 'Null', 'null']))) {
                $types[] = 'null';
            }

            if ($node->type instanceof Node\NullableType) {
                $types[] = $this->getTypeFromNode($node->type);
            }

            if ($node->type instanceof Node\UnionType) {
                // Iterating through the union types.
                foreach ($node->type as $item) {
                    $types[] = $this->getTypeFromNode($item);
                }
            }
        } else {
            if ($node instanceof Node\Identifier) {
                $types[] = $node->name;
            }

            if ($node instanceof Node\Name) {
                if (count($node->parts) > 0) {
                    $types[] = implode('', $node->parts);
                }
            }
        }

        return $types;
    }
}