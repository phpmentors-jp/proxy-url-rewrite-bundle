<?php
/*
 * Copyright (c) 2015 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReplaceAssetExtensionAndPakcagesDefinitionPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('twig.extension.assets') && $container->hasDefinition('assets.packages')) {
            $container->getDefinition('phpmentors_proxy_url_rewrite.proxy_asset_extension')->setArguments($container->getDefinition('twig.extension.assets')->getArguments());
            $container->setAlias('twig.extension.assets', 'phpmentors_proxy_url_rewrite.proxy_asset_extension');

            $container->getDefinition('phpmentors_proxy_url_rewrite.proxy_packages')->setArguments($container->getDefinition('assets.packages')->getArguments());
            $container->setAlias('assets.packages', 'phpmentors_proxy_url_rewrite.proxy_packages');
        }
    }
}
