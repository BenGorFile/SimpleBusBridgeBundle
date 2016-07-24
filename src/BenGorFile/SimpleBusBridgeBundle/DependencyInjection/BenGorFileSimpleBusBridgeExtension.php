<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\SimpleBusBridgeBundle\DependencyInjection;

use BenGorFile\FileBundle\DependencyInjection\Configuration;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Recorder\HandlesRecordedMessagesMiddleware;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Simple bus bridge bundle extension class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileSimpleBusBridgeExtension extends Extension implements PrependExtensionInterface, SimpleBusTaggerExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('middlewares.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $container->getExtensionConfig('ben_gor_file');
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bengor_file.config', $config);
    }

    /**
     * {@inheritdoc}
     */
    public function addMiddlewareTags(ContainerBuilder $container, $file)
    {
        // Related with Command Bus
        $container->setDefinition(
            'bengor_file.simple_bus_bridge_bundle.' . $file . '_command_bus.delegates_to_message_handler_middleware',
            (new Definition(DelegatesToMessageHandlerMiddleware::class))->addTag(
                'bengor_file_' . $file . '_command_bus_middleware', ['priority' => '-1000']
            )
        );
        $container->getDefinition(
            'bengor_file.simple_bus_bridge_bundle.finishes_command_before_handling_next_middleware'
        )->addTag(
            'bengor_file_' . $file . '_command_bus_middleware', ['priority' => '1000']
        );

        // Related with Event Bus
        $container->setDefinition(
            'bengor_file.simple_bus_bridge_bundle.' . $file . '_event_bus.delegates_to_message_handler_middleware',
            (new Definition(NotifiesMessageSubscribersMiddleware::class))->addTag(
                'bengor_file_' . $file . '_event_bus_middleware', ['priority' => '-1000']
            )
        )->addTag(
            'bengor_file_' . $file . '_command_bus_middleware', ['priority' => '200']
        );
        $container->setDefinition(
            'bengor_file.simple_bus_bridge_bundle.' . $file . '_event_bus.handles_recorded_messages_middleware',
            (new Definition(HandlesRecordedMessagesMiddleware::class))->addTag(
                'bengor_file_' . $file . '_event_bus_middleware', ['priority' => '-1000']
            )
        )->addTag(
            'bengor_file_' . $file . '_command_bus_middleware', ['priority' => '200']
        );

        return $container;
    }
}
