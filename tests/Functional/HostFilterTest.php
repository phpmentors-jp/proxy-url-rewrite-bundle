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

/**
 * @since Class available since Release 1.2.0
 */
class HostFilterTest extends AbstractTestCase
{
    public function filterData()
    {
        return array(
            array('/foo/bar/', true, 'http://backend1.example.com/foo/bar/url-rewriting-in-controllers/'),
            array('/foo/bar/', false, 'http://backend1.example.com/foo/bar/url-rewriting-in-controllers/'),
            array('//example.com/foo/bar/', true, 'http://baz.example.com/foo/bar/url-rewriting-in-controllers/'),
            array('//example.com/foo/bar/', false, 'http://example.com/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com/foo/bar/', true, 'http://baz.example.com/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com/foo/bar/', false, 'http://example.com/foo/bar/url-rewriting-in-controllers/'),
            array('https://example.com/foo/bar/', true, 'https://baz.example.com/foo/bar/url-rewriting-in-controllers/'),
            array('https://example.com/foo/bar/', false, 'https://example.com/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com:8180/foo/bar/', true, 'http://baz.example.com:8180/foo/bar/url-rewriting-in-controllers/'),
            array('http://example.com:8180/foo/bar/', false, 'http://example.com:8180/foo/bar/url-rewriting-in-controllers/'),
        );
    }

    /**
     * @test
     * @dataProvider filterData
     *
     * @param string $proxyUrl
     * @param bool   $proxyHostFilterService
     * @param string $rewroteUrl
     */
    public function filter($proxyUrl, $proxyHostFilterService, $rewroteUrl)
    {
        $client = $this->createClient(array('config' => function (ContainerBuilder $container) use ($proxyUrl, $proxyHostFilterService) {
            $config = array(
                'path' => '!^.*!',
                'proxy_url' => $proxyUrl,
            );
            if ($proxyHostFilterService) {
                $config['proxy_host_filter_service'] = 'phpmentors_proxy_url_rewrite_test.proxy_host_filter';
            }

            $container->loadFromExtension('framework', array(
                'secret' => '$ecret',
            ));
            $container->loadFromExtension('phpmentors_proxy_url_rewrite', array(
                'proxy_urls' => array(
                    'foo' => $config,
            ), ));
        }));

        $client->request('GET', sprintf('http://backend1.example.com:8080/url-rewriting-in-controllers/?referenceType=%s', UrlGeneratorInterface::ABSOLUTE_URL));

        $this->assertThat($client->getResponse()->getStatusCode(), $this->equalTo(200), $client->getResponse()->getContent());
        $this->assertThat($client->getCrawler()->filterXpath("//*[@id='generateUrl']")->text(), $this->equalTo($rewroteUrl));
    }
}
