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

class ProxyUrlCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $proxyUrls = array();

    /**
     * @param string   $routeName
     * @param ProxyUrl $proxyUrl
     */
    public function add($routeName, ProxyUrl $proxyUrl)
    {
        $this->proxyUrls[$routeName] = $proxyUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->proxyUrls);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->proxyUrls);
    }
}
