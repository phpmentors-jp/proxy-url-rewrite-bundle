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

namespace PHPMentors\ProxyURLRewriteBundle\ProxyUrl;

use PHPMentors\DomainKata\Service\ServiceInterface;

class ProxyUrlMatcher implements ServiceInterface
{
    /**
     * @var ProxyUrlCollection
     */
    private $proxyUrlCollection;

    /**
     * @param ProxyUrlCollection $proxyUrlCollection
     */
    public function __construct(ProxyUrlCollection $proxyUrlCollection)
    {
        $this->proxyUrlCollection = $proxyUrlCollection;
    }

    /**
     * @param string $pathinfo
     *
     * @return ProxyUrl|null
     */
    public function match($pathinfo)
    {
        $pathinfo = rawurldecode($pathinfo);
        foreach ($this->proxyUrlCollection as $proxyUrl) { /* @var $proxyUrl ProxyUrl */
            if (preg_match($proxyUrl->getTarget(), $pathinfo)) {
                return $proxyUrl;
            }
        }

        return null;
    }
}
