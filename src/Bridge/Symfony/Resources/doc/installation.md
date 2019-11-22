## Installation

### Composer

`composer require camelot/sitemap`

### app/AppKernel.php

Register the `CamelotSitemapBundle`:

```php,no_execute
// src/Kernel.php
final class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Camelot\Sitemap\Bridge\Symfony\CamelotSitemapBundle(),
        );
    }
}
```

## Bundle Configuration

### config.yml

The following options are available in the `app/config/config.yml` file:

```yaml
camelot_sitemap:
    host:  http://www.foo.com
    limit: 50000
```

**Note:**

> The `host` will be prepended to the sitemap filename (used for sitemap index)
> The `limit` is the number of url allowed in the same sitemap, if defined it will create a sitemap index

### Routing

If you don't want to use the console to generate the sitemap, import the
routes:

```yaml
camelot_sitemap:
    resource: "@CamelotSitemapBundle/Resources/config/routing.yml"
```

This will make the sitemap available from the `/sitemap.xml` URL.


## Next steps

[Return to the index](https://github.com/CamelotProject/sitemap/blob/master/src/Bridge/Symfony/Resources/doc/index.md) or [configure your sitemap](https://github.com/CamelotProject/sitemap/blob/master/src/Bridge/Symfony/Resources/doc/configuration.md)
