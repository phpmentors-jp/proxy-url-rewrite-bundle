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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @since Class available since Release 1.4.0
 */
class TrailingSlashRedirectionTest extends AbstractTestCase
{
    public function redirectWithProxyUrlData()
    {
        $data = array(
            array('/trailing-slash-redirection-with-slash', true, 'http://www.example.com/foo/trailing-slash-redirection-with-slash/'),
            array('/trailing-slash-redirection-with-slash/', false, null),
            array('/trailing-slash-redirection-without-slash', false, null),
        );

        if (Kernel::VERSION_ID >= 40000) {
            $data[] = array('/trailing-slash-redirection-without-slash/', true, 'http://www.example.com/foo/trailing-slash-redirection-without-slash');
        }

        return $data;
    }

    /**
     * @test
     * @dataProvider redirectWithProxyUrlData
     */
    public function redirectWithProxyUrl($pathToBeRequested, $redirection, $urlToBeRedirected)
    {
        $proxyUrl = 'http://www.example.com/foo';
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

        $client->request('GET', 'http://backend1.example.com:8080'.$pathToBeRequested);

        if ($redirection) {
            $this->assertThat($client->getResponse()->getStatusCode(), $this->equalTo(301), $client->getResponse()->getContent());
            $this->assertThat($client->getResponse()->headers->get('Location'), $this->equalTo($urlToBeRedirected));
        } else {
            $this->assertThat($client->getResponse()->getStatusCode(), $this->equalTo(200), $client->getResponse()->getContent());
        }
    }
}
