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

class RegisterFileLoadersPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('cool_sonata_translation.file_loader_resolver')) {
            return;
        }

        $resolver = $container->getDefinition('cool_sonata_translation.file_loader_resolver');
        $loaders  = $container->findTaggedServiceIds('translation.loader');

        foreach ($loaders as $id => $tagAttributes) {
            /**
             * At this point there is no means to identify the type of loader we have, so we use
             * all tagged loaders to fill the resolver.
             * Also we need to add the references, since we cannot make sure the services are
             * already instantiated
             */
            foreach ($tagAttributes as $attributes) {
                /** @var \Symfony\Component\Translation\Loader\LoaderInterface $loader */
                $resolver->addMethodCall(
                    'registerLoader',
                    array(
                        new Reference($id),
                        $attributes['alias']
                    )
                );
            }
        }

        // additional file loaders can be registered in the configuration
        if ($container->hasParameter('cool_sonata_translation.translation_loaders')) {
            $additionalLoaders = $container->getParameter('cool_sonata_translation.translation_loaders');

            foreach ($additionalLoaders as $extension => $loaderId) {
                $resolver->addMethodCall(
                    'registerLoader',
                    array(
                        new Reference($loaderId),
                        $extension
                    )
                );
            }
        }
    }
}
