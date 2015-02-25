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

namespace PHPMentors\ProxyURLRewriteBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReplaceCoreAssetsHelperDefinitionPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('templating.helper.assets'); /* @var $definition \Symfony\Component\DependencyInjection\Definition */
        $extendedDefinition = $container->getDefinition('phpmentors_proxy_url_rewrite.core_assets_helper'); /* @var $extendedDefinition \Symfony\Component\DependencyInjection\Definition */

        $definition->setClass($extendedDefinition->getClass());
        foreach ($extendedDefinition->getMethodCalls() as $methodCall) {
            $definition->addMethodCall($methodCall[0], $methodCall[1]);
        }

        $container->removeDefinition('phpmentors_proxy_url_rewrite.core_assets_helper');
    }
}
