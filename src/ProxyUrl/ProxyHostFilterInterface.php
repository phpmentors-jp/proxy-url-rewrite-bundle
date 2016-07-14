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

/**
 * @since Interface available since Release 1.2.0
 */
interface ProxyHostFilterInterface extends ServiceInterface
{
    /**
     * @param string
     *
     * @return string
     */
    public function filter($host);
}
