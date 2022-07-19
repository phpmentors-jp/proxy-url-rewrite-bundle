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

namespace PHPMentors\ProxyURLRewriteBundle\EventListener;

use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlMatcher;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

class ProxyUrlRewriteListener
{
    /**
     * @var ProxyUrlMatcher
     *
     * @since Property available since Release 1.1.0
     */
    private $proxyUrlMatcher;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param ProxyUrlMatcher $proxyUrlMatcher
     *
     * @since Method available since Release 1.1.0
     */
    public function setProxyUrlMatcher(ProxyUrlMatcher $proxyUrlMatcher)
    {
        $this->proxyUrlMatcher = $proxyUrlMatcher;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param RequestEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->getRequestType() == HttpKernelInterface::MASTER_REQUEST) {
            $matchedProxyUrl = $this->proxyUrlMatcher->match($this->router->getContext()->getPathInfo());
            if ($matchedProxyUrl !== null) {
                $this->router->getContext()->setBaseUrl($matchedProxyUrl->getPath().$this->router->getContext()->getBaseUrl());
                $this->router->getContext()->setScheme($matchedProxyUrl->getScheme());

                if ($matchedProxyUrl->getHost() !== null) {
                    $this->router->getContext()->setHost($matchedProxyUrl->getHost());
                }

                if ($matchedProxyUrl->getPort() !== null) {
                    if ($this->router->getContext()->getScheme() == 'http') {
                        $this->router->getContext()->setHttpPort($matchedProxyUrl->getPort());
                    } elseif ($this->router->getContext()->getScheme() == 'https') {
                        $this->router->getContext()->setHttpsPort($matchedProxyUrl->getPort());
                    }
                }
            }
        }
    }
}
