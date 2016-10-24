<?php

/*
 * This file is part of the CoolSonataTranslationBundle package.
 *
 * (c) Alberto Brino <alberto@coolshop.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Coolshop\CoolSonataTranslationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass that adds additional resources to the Translator.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AddResourcePass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('cool_sonata_translation.resources');
        $translator = $container->findDefinition('translator.default');

        if (null !== $translator) {
            foreach ($resources as $locale => $domains) {
                foreach ($domains as $domain) {
                    $translator->addMethodCall(
                        'addResource',
                        array(
                            'db',
                            new Reference('cool_sonata_translation.translation_manager_resource'),
                            $locale,
                            $domain,
                        )
                    );
                }
            }
        }
    }
}
