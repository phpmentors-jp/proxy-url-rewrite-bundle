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

class MatchedProxyUrl extends ProxyUrl
{
    /**
     * @var string
     */
    private $routeName;

    /**
     * @param string $target
     * @param string $path
     * @param string $host
     * @param string $scheme
     * @param string $routeName
     */
    public function __construct($target, $path, $host, $scheme, $routeName)
    {
        parent::__construct($target, $path, $host, $scheme);

        $this->routeName = $routeName;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }
}
