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

namespace PHPMentors\ProxyURLRewriteBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlRewritingInTemplatesTest extends WebTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $_SERVER['KERNEL_DIR'] = __DIR__.'/app';
        $_SERVER['SYMFONY__SECRET'] = hash('sha1', uniqid(mt_rand()));

        $this->removeCacheDir();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->removeCacheDir();
    }

    /**
     * {@inheritDoc}
     */
    protected static function createKernel(array $options = array())
    {
        $kernel = KernelTestCase::createKernel($options);
        if (array_key_exists('config', $options)) {
            $kernel->setConfig($options['config']);
        }

        return $kernel;
    }

    protected function removeCacheDir()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove($_SERVER['KERNEL_DIR'].'/cache/test');
    }

    /**
     * @return array
     */
    public function rewriteUrlInAssetData()
    {
        return array(
            array('/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/bundles/test/foo.png'),
            array('//example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/bundles/test/foo.png'),
            array('//example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/foo/bar/bundles/test/foo.png'),
            array('http://example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/bundles/test/foo.png'),
            array('http://example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_URL, 'http://example.com/foo/bar/bundles/test/foo.png'),
            array('https://example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/bundles/test/foo.png'),
            array('https://example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_URL, 'https://example.com/foo/bar/bundles/test/foo.png'),
        );
    }

    /**
     * @test
     * @dataProvider rewriteUrlInAssetData
     */
    public function rewriteUrlInAsset($proxyUrl, $referenceType, $rewroteUrl)
    {
        $client = $this->createClient(array('config' => function (ContainerBuilder $container) use ($proxyUrl) {
            $container->loadFromExtension('phpmentors_proxy_url_rewrite', array(
                'proxy_urls' => array(
                    'foo' => array(
                        'path' => '!^.*!',
                        'proxy_url' => $proxyUrl,
                    ),
                ),
            ));
        }));

        $client->request('GET', sprintf('http://backend1.example.com:8080/url-rewriting-in-templates/?referenceType=%s', $referenceType));

        $this->assertThat($client->getResponse()->getStatusCode(), $this->equalTo(200), $client->getResponse()->getContent());
        $this->assertThat($client->getCrawler()->filterXpath("//*[@id='asset']")->text(), $this->equalTo($rewroteUrl));
    }
}
