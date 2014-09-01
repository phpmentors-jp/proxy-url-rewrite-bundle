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

namespace PHPMentors\ProxyURLRewriteBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\Compiler\TemplatingPass;

use PHPMentors\ProxyURLRewriteBundle\DependencyInjection\Compiler\ReplaceCoreAssetsHelperDefinitionPass;
use PHPMentors\ProxyURLRewriteBundle\DependencyInjection\PHPMentorsProxyURLRewriteExtension;

class PHPMentorsProxyURLRewriteBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $beforeOptimizationPasses = $container->getCompilerPassConfig()->getBeforeOptimizationPasses();
        for ($i = 0; $i < count($beforeOptimizationPasses); ++$i) {
            if ($beforeOptimizationPasses[$i] instanceof TemplatingPass) {
                $templatingPassIndex = $i;
                break;
            }
        }

        array_splice($beforeOptimizationPasses, $templatingPassIndex, 0, array(new ReplaceCoreAssetsHelperDefinitionPass()));
        $container->getCompilerPassConfig()->setBeforeOptimizationPasses($beforeOptimizationPasses);
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new PHPMentorsProxyURLRewriteExtension();
        }

        return $this->extension;
    }
}
