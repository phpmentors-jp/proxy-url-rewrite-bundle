<?php
/*
 * Copyright (c) 2015 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\Asset;

use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlCollection;
use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\UrlMatcher;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @since Class available since Release 1.1.0
 */
class ProxyPackages extends Packages
{
    /**
     * @var ProxyUrlCollection
     */
    private $proxyUrlCollection;

    /**
     * @param ProxyUrlCollection $proxyUrlCollection
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
        if (preg_match('/^absolute=(\d)/', $path, $matches)) {
            $absolute = (bool) $matches[1];
            $path = substr($path, strlen($matches[0]));
        } else {
            $absolute = UrlGeneratorInterface::ABSOLUTE_PATH;
        }

        $url = parent::getPackage($packageName)->getUrl($path);
        if (strpos($url, '://') !== false || strpos($url, '//') === 0) {
            return $url;
        }

        $urlMatcher = new UrlMatcher($this->proxyUrlCollection);
        $matchedProxyUrl = $urlMatcher->match($url);
        if ($matchedProxyUrl === null) {
            return $url;
        }

        $routeCollection = new RouteCollection();
        $routeCollection->add($matchedProxyUrl->getId(), new Route('/'));

        $requestContext = new RequestContext($matchedProxyUrl->getPath());
        if ($matchedProxyUrl->getHost() !== null) {
            $requestContext->setHost($matchedProxyUrl->getHost());
        }
        if ($matchedProxyUrl->getScheme() !== null) {
            $requestContext->setScheme($matchedProxyUrl->getScheme());
        }

        $urlGenerator = new UrlGenerator($routeCollection, $requestContext);

        return $urlGenerator->generate($matchedProxyUrl->getId(), array(), $absolute).ltrim($url, '/');
    }
}
