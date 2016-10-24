<?php

/*
 * This file is part of the CoolSonataTranslationBundle package.
 *
 * (c) Alberto Brino <alberto@coolshop.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Coolshop\CoolSonataTranslationBundle\Config\Resource;

use Coolshop\CoolSonataTranslationBundle\Translation\DatabaseLoader;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Resource plugging the TranslationManager into the Symfony Translator.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationManagerResource implements ResourceInterface
{
    /**
     * @var DatabaseLoader
     */
    private $loader;

    /**
     * @param DatabaseLoader $loader
     */
    public function __construct(DatabaseLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritDoc}
     */
    public function isFresh($timestamp)
    {
        return $this->loader->isFresh($timestamp);
    }

    /**
     * {@inheritDoc}
     */
    public function getResource()
    {
        return $this->loader;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return 'DatabaseLoader';
    }
}
