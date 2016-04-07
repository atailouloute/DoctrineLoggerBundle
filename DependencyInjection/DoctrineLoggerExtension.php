<?php

namespace ATailouloute\DoctrineLoggerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Ahmed TAILOULOUTE <ahmed.tailouloute@gmail.com>
 */
class DoctrineLoggerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('doctrine_logger.log_entity_creation',    $config['log_entity_creation']);
        $container->setParameter('doctrine_logger.log_entity_deletion',    $config['log_entity_deletion']);
        $container->setParameter('doctrine_logger.log_entity_deletion',    $config['log_entity_update']);
        $container->setParameter('doctrine_logger.object_formatter_class', $config['object_formatter_class']);
        $container->setParameter('doctrine_logger.output_formatter_class', $config['output_formatter_class']);
        $container->setParameter('doctrine_logger.skip_entities',          $config['skip_entities']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $doctrineLoggerDef = $container->getDefinition('doctrine_logger');

        $doctrineLoggerDef->addMethodCall('setLoggingEntityCreationEnabled', array($config['log_entity_creation']));
        $doctrineLoggerDef->addMethodCall('setLoggingEntityDeletionEnabled', array($config['log_entity_deletion']));
        $doctrineLoggerDef->addMethodCall('setLoggingEntityUpdateEnabled',   array($config['log_entity_update']));
        $doctrineLoggerDef->addMethodCall('setSkippedEntities',              array($config['skip_entities']));

        if (!is_null($config['output_formatter_class'])) {
            $container
                ->getDefinition('output_formatter')
                ->setClass($config['output_formatter_class']);
        }

        if (!is_null($config['object_formatter_class'])) {
            $container
                ->getDefinition('object_formatter')
                ->setClass($config['object_formatter_class']);
        }
    }
}
