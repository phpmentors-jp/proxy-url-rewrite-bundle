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

namespace PHPMentors\ProxyURLRewriteBundle\Templating;

use Symfony\Bridge\Twig\Extension\AssetExtension;

/**
 * @since Class available since Release 1.1.0
 */
class ProxyAssetExtension extends AssetExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAssetUrl($path, $packageName = null, $absolute = false, $version = null)
    {
        return parent::getAssetUrl(sprintf('absolute=%s'.$path, $absolute ? '1' : '0'), $packageName, $absolute, $version);
    }
}
