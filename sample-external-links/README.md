## Synopsis

This extension add external links, such as ebay.com, amazon.com product links to magento products
as extension attributes.


The example of adding extension attributes with plugin could be find in this extension

The list of feeds can be accessed at the following url:

Extension Attributes can be accessible in product object by path:
extension_attributes -> external_links -> [Array of Links could be find here]

## Motivation

To demonstrate how to add extension attributes to product or to list of products

## Technical features

### API

In order to get product or list of products by Magento API you need to do API request to appropriate service.
In Response you will see product object with described extension attributes
You can find them by path, introduced below

### Product Repository Plugin

You can find plugin here: {extension_folder}/Model/Plugin/Product/Repository
afterGet, afterGetList, afterSave - this methods are listen ProductRepositoryInterface in order to add there own attributes

### External Links Loader

External links are loaded in plugin with ExternalLinks/Loader help.
You can get few external links by product id

## Installation

This module is intended to be installed using composer.
After the code is marshalled by composer, enable the module by adding it the list of enabled modules in [the config](app/etc/config.php) or, if that file does not exist, installing Magento.
After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

### Database

In Database this module is represented by one table: product_external_links and next fields: link_id, link, link_type, product_id

## Tests

Unit tests are found in the [Test/Unit](Test/Unit) directory.
Api Functional Test is stored in the [Test/ApiFunctional] directory
You need to create new database for api-functional test
In order to run Api Functional Test you need to add it in dev/tests/api-functional/phpunit.xml.dist
Also you can run this test directly from your IDE.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)