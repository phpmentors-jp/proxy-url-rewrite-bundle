<?php
/*
 * Copyright (c) Atsuhiro Kubo <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @since Class available since Release 1.4.0
 */
abstract class AbstractTestCase extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $_SERVER['KERNEL_DIR'] = __DIR__.'/app';
        require_once $_SERVER['KERNEL_DIR'].'/AppKernel.php';
        $_SERVER['KERNEL_CLASS'] = 'AppKernel';

        if (self::$kernel !== null) {
            $this->removeCacheDir(self::$kernel->getCacheDir());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        if (self::$kernel !== null) {
            $this->removeCacheDir(self::$kernel->getCacheDir());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected static function createKernel(array $options = array())
    {
        $kernel = KernelTestCase::createKernel($options);
        if (array_key_exists('config', $options)) {
            $kernel->setConfig($options['config']);
        }

        return $kernel;
    }

    protected function removeCacheDir($cacheDir)
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove($cacheDir);
    }
}
