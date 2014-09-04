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

namespace PHPMentors\ProxyURLRewriteBundle\ProxyUrl;

class UrlMatcher
{
    /**
     * @var \PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlCollection
     */
    private $proxyUrlCollection;

    /**
     * @param \PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyUrlCollection $proxyUrlCollection
     */
    public function __construct(ProxyUrlCollection $proxyUrlCollection)
    {
        $this->proxyUrlCollection = $proxyUrlCollection;
    }

    /**
     * @param  string                                                          $pathinfo
     * @return \PHPMentors\ProxyURLRewriteBundle\ProxyUrl\MatchedProxyUrl|null
     */
    public function match($pathinfo)
    {
        $pathinfo = rawurldecode($pathinfo);
        foreach ($this->proxyUrlCollection as $routeName => $proxyUrl) { /* @var $proxyUrl \PHPMentors\ProxyURLRewriteBundle\Routing\ProxyUrl */
            if (preg_match($proxyUrl->getTarget(), $pathinfo)) {
                return new MatchedProxyUrl($proxyUrl->getTarget(), $proxyUrl->getPath(), $proxyUrl->getHost(), $proxyUrl->getScheme(), $routeName);
            }
        }

        return null;
    }
}
