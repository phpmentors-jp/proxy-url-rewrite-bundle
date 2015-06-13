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

namespace PHPMentors\ProxyURLRewriteBundle\EventListener;

use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlCollection;
use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\UrlMatcher;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

class ProxyUrlRewriteListener
{
    /**
     * @var ProxyUrlCollection
     */
    private $proxyUrlCollection;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param ProxyUrlCollection $proxyUrlCollection
     */
    public function setProxyUrlCollection(ProxyUrlCollection $proxyUrlCollection)
    {
        $this->proxyUrlCollection = $proxyUrlCollection;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() == HttpKernelInterface::MASTER_REQUEST) {
            $urlMatcher = new UrlMatcher($this->proxyUrlCollection);
            $matchedProxyUrl = $urlMatcher->match($this->router->getContext()->getPathInfo());
            if ($matchedProxyUrl !== null) {
                $this->router->getContext()->setBaseUrl($matchedProxyUrl->getPath().$this->router->getContext()->getBaseUrl());

                if ($matchedProxyUrl->getScheme() !== null) {
                    $this->router->getContext()->setScheme($matchedProxyUrl->getScheme());
                }

                if ($matchedProxyUrl->getHost() !== null) {
                    $this->router->getContext()->setHost($matchedProxyUrl->getHost());
                }

                if ($this->router->getContext()->getScheme() == 'http') {
                    $this->router->getContext()->setHttpPort('80');
                } elseif ($this->router->getContext()->getScheme() == 'https') {
                    $this->router->getContext()->setHttpsPort('443');
                }
            }
        }
    }
}
