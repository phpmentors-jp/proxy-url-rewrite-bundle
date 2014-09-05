<?php
/*
 * Copyright (c) 2014 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\DependencyInjection;

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
            ->fixXmlConfig('proxy_url')
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                ->end()
                ->arrayNode('proxy_urls')
                    ->useAttributeAsKey('path')
                    ->prototype('scalar')
                        ->defaultNull()
                        ->cannotBeEmpty()
                        ->validate()
                            ->always(function ($v) {
                                if (parse_url($v) === false) throw new \InvalidArgumentException(sprintf('The value of a Proxy URL cannot contain malformed URL, but got "%s".', $v));
                                return $v;
                            })
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
