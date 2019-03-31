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

class UrlRewritingInTemplatesTest extends AbstractTestCase
{
    /**
     * @return array
     */
    public function rewriteUrlInAssetData()
    {
        return array(
            array('/foo/bar/', '/foo/bar/bundles/test/foo.png'),
            array('//example.com/foo/bar/', '/foo/bar/bundles/test/foo.png'),
            array('http://example.com/foo/bar/', '/foo/bar/bundles/test/foo.png'),
            array('https://example.com/foo/bar/', '/foo/bar/bundles/test/foo.png'),
            array('http://example.com:8180/foo/bar/', '/foo/bar/bundles/test/foo.png'),
            array('https://example.com:8180/foo/bar/', '/foo/bar/bundles/test/foo.png'),
        );
    }

    /**
     * @test
     * @dataProvider rewriteUrlInAssetData
     */
    public function rewriteUrlInAsset($proxyUrl, $rewroteUrl)
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

        $client->request('GET', 'http://backend1.example.com:8080/url-rewriting-in-templates/');

        $this->assertThat($client->getResponse()->getStatusCode(), $this->equalTo(200), $client->getResponse()->getContent());
        $this->assertThat($client->getCrawler()->filterXpath("//*[@id='asset']")->text(), $this->equalTo($rewroteUrl));
    }
}
