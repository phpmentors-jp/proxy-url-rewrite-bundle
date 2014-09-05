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

error_reporting(E_ALL);

$loader = require dirname(__DIR__) . '/vendor/autoload.php'; /* @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('PHPMentors\ProxyURLRewriteBundle\\', __DIR__);
