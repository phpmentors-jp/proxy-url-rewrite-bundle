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

namespace PHPMentors\ProxyURLRewriteBundle\Functional\Bundle\TestBundle\ProxyUrl;

use PHPMentors\ProxyURLRewriteBundle\ProxyUrl\ProxyHostFilterInterface;

/**
 * @since Class available since Release 1.2.0
 */
class ProxyHostFilter implements ProxyHostFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function filter($host)
    {
        if ($host === null) {
            return null;
        }

        return 'baz.'.$host;
    }
}
