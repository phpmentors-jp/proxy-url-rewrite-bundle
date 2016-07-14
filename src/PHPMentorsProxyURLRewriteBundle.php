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

namespace PHPMentors\ProxyURLRewriteBundle;

use PHPMentors\ProxyURLRewriteBundle\DependencyInjection\Compiler\ReplaceAssetExtensionAndPakcagesDefinitionPass;
use PHPMentors\ProxyURLRewriteBundle\DependencyInjection\PHPMentorsProxyURLRewriteExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PHPMentorsProxyURLRewriteBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ReplaceAssetExtensionAndPakcagesDefinitionPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new PHPMentorsProxyURLRewriteExtension();
        }

        return $this->extension;
    }
}
