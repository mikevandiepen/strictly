<?php

namespace Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserTraits;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers\AnalyseDocblockHelper;
use Mediadevs\Strictly\Analyser\Strategy\Options\AnalyserHelpers\AnalyseFunctionalHelper;

/**
 * Trait AnalyseDocblockTrait.
 *
 * @package Mediadevs\Strictly\Analyser\Options\AnalyserTraits
 */
trait AnalyseDocblockTrait
{
    /**
     * Collecting the return from the docblock.
     *
     * @param \phpDocumentor\Reflection\Docblock $docblock
     *
     * @return string[]
     */
    protected function getReturnTypeFromDocblock(Docblock $docblock): array
    {
        $helper = new AnalyseDocblockHelper();

        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_[] $tags */
        $tags = $docblock->getTagsByName('return');

        foreach ($tags as $tag) {
            return $helper->getTypeFromDocblock($tag);
        }

        return [];
    }

    /**
     * Collecting all the parameters from the docblock.
     *
     * @param DocBlock $docblock
     *
     * @return Node\Param[]
     */
    protected function getParametersFromDocblock(Docblock $docblock): array
    {
        $helper = new AnalyseDocblockHelper();

        $parameters = [];

        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param[] $tags */
        $tags = $docblock->getTagsByName('param');

        foreach ($tags as $tag) {
            $parameters += [$tag->getVariableName() => $helper->getTypeFromDocblock($tag)];
        }

        return $parameters;
    }

    /**
     * Collecting the parameter from the docblock based upon the given parameter.
     * Returning an empty array if the parameter is untyped.
     *
     * @param \phpDocumentor\Reflection\Docblock $docblock
     * @param string                             $parameter
     *
     * @return string[]
     */
    protected function getParameterTypeFromDocblock(Docblock $docblock, string $parameter): array
    {
        $helper = new AnalyseDocblockHelper();

        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param[] $tags */
        $tags = $docblock->getTagsByName('param');

        foreach ($tags as $tag) {
            // Validating whether the parameter tag has a type based upon the given type.
            if ($tag->getVariableName() !== $parameter) {
                continue;
            }

            return $helper->getTypeFromDocblock($tag);
        }

        // No types are configured for this parameter, an empty array will be returned.
        return [];
    }

    /**
     * Collecting the property type from the docblock.
     * If "Null" is returned it means there is NO property type, if a string is returned there is a property type.
     *
     * Collecting the property from the docblock, since there can only be one property tag we'll take the first one
     * from the array and property the type.
     *
     * @param \phpDocumentor\Reflection\Docblock $docblock
     *
     * @return string[]
     */
    protected function getPropertyTypeFromDocblock(Docblock $docblock): array
    {
        $helper = new AnalyseDocblockHelper();

        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Property[] $tags */
        $tags = $docblock->getTagsByName('var');

        foreach ($tags as $tag) {
            return $helper->getTypeFromDocblock($tag);
        }

        return [];
    }

    /**
     * Validating whether the docblock is suppressed by "inheritdoc" (Parent class docblock).
     *
     * @param \phpDocumentor\Reflection\Docblock $docblock
     *
     * @return bool
     */
    protected function isSuppressedByInheritDoc(Docblock $docblock): bool
    {
        $inheritdoc = ['{@inheritdoc}', '@inheritdoc', 'inheritdoc'];

        if (in_array(strtolower($docblock->getSummary()), $inheritdoc)) {
            return true;
        }

        foreach ($docblock->getDescription()->getTags() as $tag) {
            $matchesTags = in_array(strtolower($docblock->getSummary()), $inheritdoc);

            if ($tag instanceof Generic && $matchesTags) {
                return true;
            }
        }

        return false;
    }

    /**
     * Dynamically validating whether the type of the given tag is suppressed by type in the docblock.
     * A compound type for example is "string|null", the "var", "param" and "return" tags can all have
     * compound types. Other types which will suppress are "Mixed_" and "Object_".
     *
     * If the parameter argument is set the validation will analyse that specific parameter name.
     * Because validating all parameters is not the most effective way to approach the analysis.
     *
     * @param \phpDocumentor\Reflection\DocBlock $docblock
     * @param string                             $type
     * @param string|null                        $parameter
     *
     * @return bool
     * @throws \Exception
     */
    protected function isSuppressedByType(Docblock $docblock, string $type, ?string $parameter = null): bool
    {
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Property[] $tags */
        $tags = $docblock->getTagsByName($type);

        foreach ($tags as $tag) {
            // The parameter analysis deviates from the default analysis.
            // since the analysis will be done by parameter name the approach is slightly different.
            if ($parameter !== null && $type === 'parameter') {
                if ($tag->getVariableName() !== $tag) {
                    continue;
                }

                if ($this->typeIsset($tag->getType())) return true;
                if ($this->typeIsMixed($tag->getType())) return true;
                if ($this->typeIsObject($tag->getType())) return true;
                if ($this->typeIsCompound($tag->getType())) return true;
            }

            if ($this->typeIsset($tag->getType())) return true;
            if ($this->typeIsMixed($tag->getType())) return true;
            if ($this->typeIsObject($tag->getType())) return true;
            if ($this->typeIsCompound($tag->getType())) return true;
        }

        return false;
    }

    /**
     * Analysing whether the type is set.
     *
     * @param \phpDocumentor\Reflection\Type|null $type
     *
     * @return bool
     */
    private function typeIsset(?Type $type): bool
    {
        return (bool) ($type) ? true : false;
    }

    /**
     * Analysing whether the type is of abstract Mixed_.
     *
     * @param \phpDocumentor\Reflection\Type|null $type
     *
     * @return bool
     */
    private function typeIsMixed(?Type $type): bool
    {
        return (bool) ($type instanceof Mixed_) ? true : false;
    }

    /**
     * Analysing whether the type is an object. This can be the abstract Object_ type or a
     * custom object hinted by the developer.
     *
     * @param \phpDocumentor\Reflection\Type|null $type
     *
     * @return bool
     */
    private function typeIsObject(?Type $type): bool
    {
        return (bool) (($type instanceof Object_) && (!$type->getFqsen())) ? true : false;
    }

    /**
     * Analysing whether the type is a compound type.
     * A compound type in a docblock will look like this "string|null".
     *
     * @param \phpDocumentor\Reflection\Type|null $type
     *
     * @return bool
     * @throws \Exception
     */
    private function typeIsCompound(?Type $type): bool
    {
        if ($type instanceof Compound) {
            if (2 === $type->getIterator()->count()) {
                // Ex: string|null => ?string
                foreach ($type as $t) {
                    if ($t instanceof Null_) {
                        return (bool) false;
                    }
                }
            }

            return (bool) true;
        }

        return (bool) false;
    }
}