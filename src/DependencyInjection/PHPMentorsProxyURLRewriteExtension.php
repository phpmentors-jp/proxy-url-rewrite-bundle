<?php
/*
 * Copyright (c) KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PHPMentorsProxyURLRewriteExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $this->transformConfigToContainer($config, $container);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'phpmentors_proxy_url_rewrite';
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function transformConfigToContainer(array $config, ContainerBuilder $container)
    {
        if ($config['enabled']) {
            foreach ($config['proxy_urls'] as $id => $proxyUrl) {
                if (class_exists('Symfony\Component\DependencyInjection\ChildDefinition')) {
                    $definitionClass = 'Symfony\Component\DependencyInjection\ChildDefinition';
                } else {
                    $definitionClass = 'Symfony\Component\DependencyInjection\DefinitionDecorator';
                }

                $definition = new $definitionClass('phpmentors_proxy_url_rewrite.proxy_url');
                $definition->setArguments(array($id, $proxyUrl['path'], $proxyUrl['proxy_url'], $proxyUrl['proxy_host_filter_service'] === null ? null : new Reference($proxyUrl['proxy_host_filter_service'])));

                $serviceId = 'phpmentors_proxy_url_rewrite.proxy_url.'.sha1($id);
                $container->setDefinition($serviceId, $definition);
                $container->getDefinition('phpmentors_proxy_url_rewrite.proxy_url_collection')->addMethodCall('add', array(new Reference($serviceId)));
            }
        } else {
            $container->removeDefinition('phpmentors_proxy_url_rewrite.proxy_url_rewrite_listener');
        }
    }
}
