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
    public function transform($value): array
    {
        return array_filter($this->choices, function ($bit) use ($value) {
            return $value & $bit;
        });
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value): int
    {
        $transformedValue = 0;

        foreach ($value as $label => $bit) {
            $transformedValue |= $bit;
        }

        return $transformedValue;
    }
}
