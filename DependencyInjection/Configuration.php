<?php

namespace ATailouloute\DoctrineLoggerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $builder->root('doctrine_logger')
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('log_entity_creation')->end()
                ->booleanNode('log_entity_deletion')->end()
                ->booleanNode('log_entity_update')->end()
                ->scalarNode('object_formatter_class')
                    ->defaultNull()
                ->end()
                ->scalarNode('output_formatter_class')
                    ->defaultNull()
                ->end()
                ->arrayNode('skip_entities')
                    ->prototype('scalar')
                    ->defaultValue(array())
                ->end()
            ->end();

        return $builder;
    }
}
