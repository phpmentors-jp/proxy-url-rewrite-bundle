<?php
/*
 * Copyright (c) Atsuhiro Kubo <kubo@iteman.jp>,
 * All rights reserved.
 *
 * This file is part of PHPMentorsProxyURLRewriteBundle.
 *
 * This program and the accompanying materials are made available under
 * the terms of the BSD 2-Clause License which accompanies this
 * distribution, and is available at http://opensource.org/licenses/BSD-2-Clause
 */

namespace PHPMentors\ProxyURLRewriteBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @since Class available since Release 1.4.0
 */
class TrailingSlashRedirectionListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (is_array($controller) && ($controller[0] instanceof RedirectController) && $controller[1] == 'urlRedirectAction') {
            $request = $event->getRequest();
            if ($request->attributes->has('_route')) {
                $request->attributes->set('request', $request);
                $request->attributes->set('route', $request->attributes->get('_route'));
                $request->attributes->set('ignoreAttributes', array('path', 'scheme', 'httpPort', 'httpsPort'));
                $event->setController(array($controller[0], 'redirectAction'));
            }
        }
    }
}
