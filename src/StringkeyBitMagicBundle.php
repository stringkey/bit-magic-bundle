<?php

namespace Stringkey\BitMagicBundle;

use Stringkey\BitMagicBundle\DependencyInjection\StringkeyBitMagicExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class StringkeyBitMagicBundle extends AbstractBundle
{
    /**
     * Overridden to allow for the custom extension alias.
     */
    public function getContainerExtension(): ExtensionInterface|null
    {
        if (null === $this->extension) {
            $this->extension = new StringkeyBitMagicExtension();
        }
        return $this->extension;
    }
}
