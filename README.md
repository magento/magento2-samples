## Synopsis

This project is a collection of samples to demonstrate technologies introduced in Magento 2.  You will find the most simple extension along with samples that incrementally add features to lead you through a exploration and education of the Magento 2 platform.

## Motivation

The intent is to learn by example, following our best practices for developing a modular site using Magento 2.

## Installation

Each sample is packaged and available individually at packages.magento.com.  For convenience and demonstration of our bundling of modules, we have included the [sample-bundle-all](sample-bundle-all) composer metapackage.  Including this dependency in your Magento project is the more convenient way to integrate the full set of examples. Refer to that sample for more detailed installation instructions.

In order to be able to install any of these samples you'll need to be sure that your root composer.json file contains a reference to the repository that holds them.  To do so you'll need to add the following to `composer.json`:

```json
    "repositories": [
        {
            "type": "composer",
            "url": "http://packages.magento.com/"
        }
    ]
```

The above can also be added via the composer cli with the command: 

    composer config repositories.magento composer http://packages.magento.com/

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)



