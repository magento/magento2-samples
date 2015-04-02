## Synopsis

This module contains a page which can be viewed at /servicecontractclient.
The page provides a result of usage service contracts of products module.

## Motivation

This is one of a collection of examples to demonstrate the features of Magento 2. 
The intent of this sample is to demonstrate how to use service contracts.

## Technical feature

[Block\ProductsList](Block/ProductList.php) demonstrates usage of service contracts of Magento 2.

It uses API of Catalog to get list of products and list of product types.
Also it uses API from Framework to set filters in product list.

## Installation

This module is intended to be installed using composer.
After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

## Tests

Unit tests could be found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
