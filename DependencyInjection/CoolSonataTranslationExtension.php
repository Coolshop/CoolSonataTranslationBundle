<?php

namespace Coolshop\CoolSonataTranslationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CoolSonataTranslationExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // set entity manager for translations
        $em = 'default';
        if (isset($config['database']['entity_manager'])
            && $config['database']['entity_manager'] != 'default'
        ) {
            $em = $config['database']['entity_manager'];
        }


        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['SonataPageBundle'])) {
            $loader->load('sonata-page.yml');
        }
        
        //IF IS SERVICE
        if (count($config['localeManager']) == 1 && $container->hasDefinition(str_replace('@', '', $config['localeManager'][0]))) {
            $container->setAlias('cool_sonata_translation.locale_manager', str_replace('@', '', $config['localeManager'][0]));
        
        } else {
            // USE LOCALES AS ARRAY CONFIGURATIONS
            $container->setParameter('cool_sonata_translation.locale_manager.locales', $config['localeManager']);
            $container->setAlias('cool_sonata_translation.locale_manager', 'cool_sonata_translation.locale_manager.config');

            $def = $container->getDefinition('cool_sonata_translation.locale_manager.config');
            $def->setArguments(array(
                $container->getParameter('cool_sonata_translation.locale_manager.locales')
            ));
        };

        $this->registerContainerParametersRecursive($container, $this->getAlias(), $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param String $alias
     * @param array $config
     */
    protected function registerContainerParametersRecursive(ContainerBuilder $container, $alias, $config)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($config),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $value) {
            $path = array();
            for ($i = 0; $i <= $iterator->getDepth(); $i++) {
                $path[] = $iterator->getSubIterator($i)->key();
            }
            $key = $alias.'.'.implode(".", $path);
            $container->setParameter($key, $value);
        }
    }
}
