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

namespace PHPMentors\ProxyURLRewriteBundle\Templating\Helper;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlCollection;
use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\UrlMatcher;

class CoreAssetsHelper extends \Symfony\Component\Templating\Helper\CoreAssetsHelper
{
    /**
     * @var \PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlCollection
     */
    private $proxyUrlCollection;

    /**
     * @param \PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlCollection $proxyUrlCollection
     */
    public function setProxyUrlCollection(ProxyUrlCollection $proxyUrlCollection)
    {
        $this->proxyUrlCollection = $proxyUrlCollection;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl($path, $packageName = null)
    {
        $url = parent::getUrl($path, $packageName);
        if (strpos($url, '://') !== false || strpos($url, '//') === 0) {
            return $url;
        }

        $urlMatcher = new UrlMatcher($this->proxyUrlCollection);
        $matchedProxyUrl = $urlMatcher->match($url);
        if ($matchedProxyUrl !== null) {
            $routeCollection = new RouteCollection();
            $routeCollection->add($matchedProxyUrl->getRouteName(), new Route('/'));

            $requestContext = new RequestContext($matchedProxyUrl->getPath());
            if ($matchedProxyUrl->getHost() !== null) {
                $requestContext->setHost($matchedProxyUrl->getHost());
            }
            if ($matchedProxyUrl->getScheme() !== null) {
                $requestContext->setScheme($matchedProxyUrl->getScheme());
            }

            $urlGenerator = new UrlGenerator($routeCollection, $requestContext);

            return $urlGenerator->generate($matchedProxyUrl->getRouteName(), array(), UrlGeneratorInterface::ABSOLUTE_PATH).ltrim($url, '/');
        } else {
            return $url;
        }
    }
}
