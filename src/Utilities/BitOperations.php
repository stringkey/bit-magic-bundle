<?php

namespace Stringkey\BitMagicBundle\Utilities;

class BitOperations
{
    /**
     * @description Counts the number of bits in a 32 bit word
     * @param int $value
     * @return int
     */
    public static function bitCount32(int $value): int
    {
        if ($value > 0xffffffff) {
            throw new \LogicException(__METHOD__ . ' expects a value that fits a 32 bit integer');
        }
        $count = $value - (($value >> 1) & 0x55555555);

        $count = (($count >> 2) & 0x33333333) + ($count & 0x33333333);
        $count = (($count >> 4) + $count) & 0x0F0F0F0F;
        $count = (($count >> 8) + $count) & 0x00FF00FF;
        $count = (($count >> 16) + $count) & 0x0000FFFF;

        return $count;
    }

    public static function bitCountKernighan(int $value): int
    {
        for($count = 0; $value != 0; $count++) {
            $value &= $value - 1;
        }
        return $count;
    }

    public static function flipEndian32(int $value): int
    {
        $flippedValue =  $value & 0x000000FF << 24;
        $flippedValue |= $value & 0x0000FF00 <<  8;
        $flippedValue |= $value & 0x00FF0000 >>  8;
        $flippedValue |= $value & 0xFF000000 >> 24;

        return $flippedValue;
    }

    public static function createOptions(int $numberOfBits, int $visibilityMask): array
    {
        $options = [];
        for ($i = 0; $i < $numberOfBits; $i++) {
            $bitValue = 1 << $i;
            if ($bitValue & $visibilityMask) {
                $options[$i] = $bitValue;
            }
        }
        return $options;
    }
}
