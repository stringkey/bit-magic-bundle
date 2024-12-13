<?php

namespace Stringkey\BitMagicBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class BitmaskToArrayTransformer implements DataTransformerInterface
{
    /** @var array */
    private array $choices;

    /**
     * @param array $choices
     */
    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }

    /**
     * @inheritdoc
     */
    public function transform($value)
    {
        $transformedValue = [];

        foreach ($this->choices as $label => $bit) {
            if (boolval($value & $bit)) {
                $transformedValue[$label] = $bit;
            }
        }

        return $transformedValue;
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
        /** @var int $transformedValue */
        $transformedValue = 0;
        foreach ($value as $label => $bit) {
            $transformedValue |= $bit;
        }

        return $transformedValue;
    }
}
