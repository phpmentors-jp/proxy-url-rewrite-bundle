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

use PHPMentors\DomainKata\Entity\EntityCollectionInterface;
use PHPMentors\DomainKata\Entity\EntityInterface;

class ProxyUrlCollection implements EntityCollectionInterface
{
    /**
     * @var array
     */
    private $proxyUrls = array();

    /**
     * {@inheritdoc}
     */
    public function add(EntityInterface $entity)
    {
        assert($entity instanceof ProxyUrl);

        $this->proxyUrls[$entity->getId()] = $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->proxyUrls);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->proxyUrls);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->proxyUrls)) {
            return null;
        }

        return $this->proxyUrls[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function remove(EntityInterface $entity)
    {
        assert($entity instanceof ProxyUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->proxyUrls;
    }
}
