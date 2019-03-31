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

namespace PHPMentors\ProxyURLRewriteBundle\Functional;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlRewritingInControllersTest extends AbstractTestCase
{
    public function rewriteUrlInGenerateUrlData()
    {
        return array(
            array('/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/url-rewriting-in-controllers/'),
            array('/foo/bar/', UrlGeneratorInterface::NETWORK_PATH, '//backend1.example.com/foo/bar/url-rewriting-in-controllers/'),
            array('//example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/url-rewriting-in-controllers/'),
            array('//example.com/foo/bar/', UrlGeneratorInterface::NETWORK_PATH, '//example.com/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com/foo/bar/', UrlGeneratorInterface::NETWORK_PATH, '//example.com/foo/bar/url-rewriting-in-controllers/'),
            array('https://example.com/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/url-rewriting-in-controllers/'),
            array('https://example.com/foo/bar/', UrlGeneratorInterface::NETWORK_PATH, '//example.com/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com:8180/foo/bar/', UrlGeneratorInterface::ABSOLUTE_PATH, '/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com:8180/foo/bar/', UrlGeneratorInterface::NETWORK_PATH, '//example.com:8180/foo/bar/url-rewriting-in-controllers/'),
        );
    }

    /**
     * @test
     * @dataProvider rewriteUrlInGenerateUrlData
     */
    public function rewriteUrlInGenerateUrl($proxyUrl, $referenceType, $rewroteUrl)
    {
        $client = $this->createClient(array('config' => function (ContainerBuilder $container) use ($proxyUrl) {
            $container->loadFromExtension('framework', array(
                'secret' => '$ecret',
            ));
            $container->loadFromExtension('phpmentors_proxy_url_rewrite', array(
                'proxy_urls' => array(
                    'foo' => array(
                        'path' => '!^.*!',
                        'proxy_url' => $proxyUrl,
                    ),
                ),
            ));
        }));

        $client->request('GET', sprintf('http://backend1.example.com:8080/url-rewriting-in-controllers/?referenceType=%s', $referenceType));

        $this->assertThat($client->getResponse()->getStatusCode(), $this->equalTo(200), $client->getResponse()->getContent());
        $this->assertThat($client->getCrawler()->filterXpath("//*[@id='generateUrl']")->text(), $this->equalTo($rewroteUrl));
    }
}
