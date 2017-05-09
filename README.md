```The sample code included in this repository was developed for Magento 2.0.x. Please be aware of this, and also know that PRs are welcomed to help us improve and/or update the 2.0.x code samples and extensions! We welcome your feedback and ideas about also creating a series of 2.1.x code examples; in our shared reality of constrained resources and a million fun projects to work on, we acknowledge that it will take some time for us to provide the 2.1.x samples.```

## Synopsis

This project is a collection of samples to demonstrate technologies introduced in Magento 2. You will find the most simple extension along with samples that incrementally add features to lead you through a exploration and education of the Magento 2 platform.

## Motivation

The intent is to learn by example, following our best practices for developing a modular site using Magento 2.

## Installation

Each sample is packaged and available individually at `repo.magento.com`.  For convenience and demonstration of our bundling of modules, we have included the [sample-bundle-all](sample-bundle-all) composer metapackage.  Including this dependency in your Magento project is the more convenient way to integrate the full set of examples. Refer to that sample for more detailed installation instructions.

To install any of these samples, you'll need to be sure that your root `composer.json` file contains a reference to the repository that contains them.  To do so, add the following to `composer.json`:

```json
    "repositories": [
        {
            "type": "composer",
            "url": "http://repo.magento.com/"
        }
    ]
```

The above can also be added using the Composer command line with the command: 

    composer config repositories.magento composer http://repo.magento.com/

### Registration
New in Magento 2 is the ability to *register* modules to install anywhere under the Magento root directory; typically, under the `vendor` subdirectory.

All sample modules have a `registration.php` in their root directory with contents similar to the following:

```php
<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Magento_CommandExample',
    __DIR__
);
```

The preceding example registers the `Magento_CommandExample` component to install under Magento's `vendor` directory. For more information about component registration, see the [PHP Developer's Guide](http://devdocs.magento.com/guides/v2.0/extension-dev-guide/component-registration.html).

In addition, each module's `composer.json` references `registration.php` in its `autoload` section as follows:

```php
{
  "name": "magento/sample-module-command",
  "description": "Command example",
  "type":"magento2-module",
  "require": {
    "php": "~5.5.0|~5.6.0|~7.0.0"
  },
  "version": "1.0.0",
  "autoload": {
    "files": [ "registration.php" ],
    "psr-4": {
      "Magento\\CommandExample\\": ""
    }
  }
}
```

### PSR-4 section
Each module's `composer.json` has a [`psr-4`](https://getcomposer.org/doc/04-schema.md#psr-4) section *except* for `sample-module-theme`. Themes don't require it because they do not reference

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)



