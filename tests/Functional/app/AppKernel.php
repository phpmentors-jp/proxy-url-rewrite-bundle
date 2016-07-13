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

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * @var \Closure
     */
    private $config;

    /**
     * @var int
     */
    private static $numberOfInitializations = 0;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new PHPMentors\ProxyURLRewriteBundle\PHPMentorsProxyURLRewriteBundle(),
            new PHPMentors\ProxyURLRewriteBundle\Functional\Bundle\TestBundle\TestBundle(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');

        if ($this->config instanceof \Closure) {
            $loader->load($this->config);
        }
    }

    /**
     * @param \Closure $config
     */
    public function setConfig(\Closure $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerClass()
    {
        return parent::getContainerClass().static::$numberOfInitializations;
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeContainer()
    {
        ++static::$numberOfInitializations;

        parent::initializeContainer();
    }
}
