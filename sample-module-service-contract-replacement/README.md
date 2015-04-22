## Synopsis

An extension to replace GiftMessage service contracts of Magento 2

## Motivation

This is one of a collection of examples to demonstrate the features of Magento 2.  The intent of this sample is to demonstrate how to replace service contracts of Magento 2.

## Technical feature

Model\CartRepository and Model\ItemRepository implement service contracts of Magento 2.

Model\CartRepository implements CartRepositoryInterface of GiftMessage module.
Model\ItemRepository implements ItemRepositoryInterface of GiftMessage module.
Those models uses cache storage to save gift messages for order or order item

## Installation

This module is intended to be installed using composer.  After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

## Tests

Unit tests could be found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
