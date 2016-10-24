<?php

namespace Coolshop\CoolSonataTranslationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    static $supportedDrivers = array('orm');
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cool_sonata_translation');

        $this->addEditableSection($rootNode);

        return $treeBuilder;
    }
    
    protected function addEditableSection(ArrayNodeDefinition $node)
    {

        $node
            ->children()
                ->scalarNode('defaultDomain')->defaultValue('messages')->end()
                ->arrayNode('defaultSelections')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('nonTranslatedOnly')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('emptyPrefixes')
                    ->defaultValue(array('__', 'new_', ''))
                    ->prototype('array')->end()
                ->end()
                ->arrayNode('editable')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('mode')->defaultValue('inline')->end()
                        ->scalarNode('type')->defaultValue('textarea')->end()
                        ->scalarNode('emptytext')->defaultValue('Empty')->end()
                        ->scalarNode('placement')->defaultValue('top')->end()
                    ->end()
                ->end()
                #Locales
                ->arrayNode('localeManager')
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function($v) { return preg_split('/\s*,\s*/', $v); })
                    ->end()
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()
                #ASM inherited configs
                ->arrayNode('resources')
                    ->useAttributeAsKey('locale')
                    ->prototype('array')
                        ->treatNullLike(array(null))
                        ->beforeNormalization()
                            ->ifTrue(
                                function ($v) {
                                    return is_array($v) && count($v) == 0;
                                }
                            )
                            ->then(
                                function () {
                                    return array(null);
                                }
                            )
                        ->end()
                        ->beforeNormalization()
                            ->ifTrue(
                                function ($v) {
                                    return is_array($v) && isset($v['domain']);
                                }
                            )
                            ->then(
                                function ($v) {
                                    return $v['domain'];
                                }
                            )
                        ->end()
                        ->beforeNormalization()
                            ->ifString()
                                ->then(
                                    function ($v) {
                                        return array($v);
                                    }
                                )
                        ->end()
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
                ->scalarNode('driver')
                    ->validate()
                        ->ifNotInArray(self::$supportedDrivers)
                        ->thenInvalid(
                            'The driver %s is not supported. Please choose one of '.json_encode(self::$supportedDrivers)
                        )
                    ->end()
                    ->defaultValue('orm')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('loaders')
                    ->beforeNormalization()
                        ->ifNull()
                            ->then(
                                function () {
                                    return array();
                                }
                            )
                    ->end()
                    ->beforeNormalization()
                        ->always(function ($values) {
                            foreach ($values as $key => $value) {
                                if (is_array($value)) {
                                    $values[$value['extension']] = $value['value'];
                                    unset($values[$key]);
                                }
                            }

                            return array_merge(array(
                                'xlf' => 'translation.loader.xliff',
                                'yaml' => 'translation.loader.yml',
                            ), $values);
                        })
                    ->end()
                    ->useAttributeAsKey('extension')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('database')
                    ->children()
                        ->scalarNode('entity_manager')
                            ->defaultValue('default')
                            ->info('Optional entity manager for separate translations handling.')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
