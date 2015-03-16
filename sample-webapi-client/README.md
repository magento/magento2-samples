## Synopsis

This most basic skeleton module for Magento 2.

## Motivation

This is one of a collection of examples to demonstrate the features of Magento 2.  The intent is to learn by example, following our best practices for developing a modular site using Magento 2.

## Technical feature

This component demonstrates the modularity of Magento 2.  The [module.xml](etc/module.xml) is read by the system and used to manage isolated modules of the system.  By enabling this module, you can see how the system becomes aware of a module and loads the features packaged within a module.

## Installation

This module is intended to be installed using composer.  After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

## Tests

Any tests would typically be found in the [Test](Test) directory.  Since this module has no code, no tests are provided.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
