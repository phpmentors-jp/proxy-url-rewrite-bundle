<?php
/*
 * Copyright (c) 2014-2015 KUBO Atsuhiro <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\ProxyUrl;

use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\DomainKata\Entity\Operation\IdentifiableInterface;

class ProxyUrl implements EntityInterface, IdentifiableInterface
{
    /**
     * @var string
     *
     * @since Property available since Release 1.1.0
     */
    private $host;

    /**
     * @var int|string
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $target;

    /**
     * @param int|string $id
     * @param string     $target
     * @param string     $path
     * @param string     $host
     * @param string     $scheme
     */
    public function __construct($id, $target, $path, $host, $scheme)
    {
        $this->id = $id;
        $this->target = $target;
        $this->path = rtrim($path, '/');
        $this->host = $host;
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritDoc}
     *
     * @return int|string
     *
     * @since Method available since Release 1.1.0
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
