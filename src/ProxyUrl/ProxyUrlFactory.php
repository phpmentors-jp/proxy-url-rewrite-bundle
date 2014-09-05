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

class ProxyUrlFactory
{
    /**
     * @param  string                                             $path
     * @param  string                                             $proxyUrl
     * @return \PHPMentors\ProxyURLRewriteBundle\Routing\ProxyUrl
     */
    public function create($path, $proxyUrl)
    {
        list($proxyUrlPath, $proxyUrlHost, $proxyUrlScheme) = static::parseUrl($proxyUrl);

        return new ProxyUrl($path, $proxyUrlPath, $proxyUrlHost, $proxyUrlScheme);
    }

    /**
     * @param  string                    $url
     * @return array
     * @throws \UnexpectedValueException
     * @link http://php.net/manual/en/function.parse-url.php
     */
    public static function parseUrl($url)
    {
        $components = parse_url($url);
        if ($components === false) {
            throw new \UnexpectedValueException(sprintf('The proxy URL "%s" is malformed.', $url));
        }

        if (array_key_exists('port', $components)) {
            throw new \UnexpectedValueException(sprintf('The proxy URL "%s" cannot contain port number.', $url));
        }

        $path = array_key_exists('path', $components) ? $components['path'] : null;
        $host = array_key_exists('host', $components) ? $components['host'] : null;
        $scheme = array_key_exists('scheme', $components) ? $components['scheme'] : null;

        if (strpos($path, '//') === 0) {
            $endOfHostPosition = strpos($path, '/', 2);
            if ($endOfHostPosition === false) {
                $host = substr($path, 2);
                $path = null;
            } elseif ($endOfHostPosition == 2) {
                throw new \UnexpectedValueException(sprintf('The proxy URL "%s" is malformed.', $url));
            } elseif ($endOfHostPosition > 2) {
                $host = substr($path, 2, $endOfHostPosition - 2);
                $path = substr($path, $endOfHostPosition);
            }
        }

        return array($path, $host, $scheme);
    }
}
