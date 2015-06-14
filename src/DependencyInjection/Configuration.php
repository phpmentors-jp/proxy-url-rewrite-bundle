<?php
/*
 * Copyright (c) 2014-2015 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\DependencyInjection;

use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('phpmentors_proxy_url_rewrite')
            ->canBeEnabled()
            ->fixXmlConfig('proxy_url')
            ->children()
                ->arrayNode('proxy_urls')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('proxy_url')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->always(function ($v) {
                                        try {
                                            ProxyUrlFactory::parseUrl($v);
                                        } catch (\UnexpectedValueException $e) {
                                            throw new \InvalidArgumentException($e->getMessage());
                                        }

                                        return $v;
                                    })
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ;

        return $treeBuilder;
    }
}
