## Synopsis

An extension to link one webpage to another.

## Motivation

This extension demonstrate how to link from one webpage to another using Magento's url framework. 

## Technical feature

Magento\Framework\UrlInterface
 - Library component that provides logic for accessing and manipulating Magento URLs

## Installation

This module is intended to be installed using composer.  
After the code is marshalled by composer, enable the module by adding it the list of enabled modules in [the config](app/etc/config.php) or, if that file does not exist, installing Magento.
After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

## Tests

Unit tests are found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
