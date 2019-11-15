## Installation

### Composer

`composer require camelot/sitemap`

### app/AppKernel.php

Register the `CamelotSitemapBundle`:

```php,no_execute
// app/AppKernel.php
class AppKernel extends Kernel
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
    base_host:         http://www.foo.com
    base_host_sitemap: http://www.foo.com
    limit:             50000
```

**Note:**

> The `base_host` will be prepended to relative urls added to the sitemap.
> The `base_host_sitemap` will be prepended to the sitemap filename (used for sitemap index)
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
