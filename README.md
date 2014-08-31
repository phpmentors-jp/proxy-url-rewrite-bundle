# PHPMentorsProxyURLRewriteBundle

A Symfony bundle to rewrite URLs for applications behind reverse proxies

[![Total Downloads](https://poser.pugx.org/phpmentors/proxy-url-rewrite-bundle/downloads.png)](https://packagist.org/packages/phpmentors/proxy-url-rewrite-bundle)
[![Latest Stable Version](https://poser.pugx.org/phpmentors/proxy-url-rewrite-bundle/v/stable.png)](https://packagist.org/packages/phpmentors/proxy-url-rewrite-bundle)
[![Latest Unstable Version](https://poser.pugx.org/phpmentors/proxy-url-rewrite-bundle/v/unstable.png)](https://packagist.org/packages/phpmentors/proxy-url-rewrite-bundle)

## Features

* URL rewriting for [Controller::generateUrl()](http://symfony.com/doc/current/quick_tour/the_controller.html#redirecting-and-forwarding) in controllers
* URL rewriting for [{{ asset('...') }}](http://symfony.com/doc/current/book/templating.html#linking-to-assets) in templates

## Installation

Composer Console can be installed using [Composer](http://getcomposer.org/).

First, add the dependency to **phpmentors/proxy-url-rewrite-bundle** into your **composer.json** file as the following:

```json
{
    "require": {
        "phpmentors/proxy-url-rewrite-bundle": "~1.0@dev"
    },
}
```

Second, update your dependencies as the following:

```console
composer update phpmentors/proxy-url-rewrite-bundle
```

## Configuration

app/AppKernel.php:

```php
...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new PHPMentors\ProxyURLRewriteBundle\PHPMentorsProxyURLRewriteBundle(),
            ...
        );
        ...
```

app/config/config_prod.yml:

```yaml
...
phpmentors_proxy_url_rewrite:
    proxy_urls:
        "!^.*!": "/foo/bar"
```

app/config/config_prod.yml:

```yaml
...
phpmentors_proxy_url_rewrite:
    enabled: false
```

## Support

If you find a bug or have a question, or want to request a feature, create an issue or pull request for it on [Issues](https://github.com/phpmentors-jp/proxy-url-rewrite-bundle/issues).

## Copyright

Copyright (c) 2014 KUBO Atsuhiro, All rights reserved.

## License

[The BSD 2-Clause License](http://opensource.org/licenses/BSD-2-Clause)
