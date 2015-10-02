## Synopsis

This module contains one command under example: section. and one ConfigOptionsList class in Setup

## Motivation

This is one of a collection of examples to demonstrate the features of Magento 2.
The intent of this sample is to demonstrate how to add custom option to setup:config:set command

## Technical feature

setup:config:set is a command for managing Magento deployment configuration. Modules are able to store their own custom
configuration in the Magento deployment configuration and later retrieve from it. Custom options will also appear in
setup:install command, allowing user to specify custom configuration during installation.

## Adding custom options to setup:config:set mechanism

* Create class ConfigOptionsList in <module_dir>/Setup which implements
Magento\Framework\Setup\ConfigOptionsListInterface

* Implement required methods:
** getOptions(): Returns list of custom options that should be added to setup:config:set command
** createConfigData(): Creates the required array structure to be stored in deployment config
** validate(): Validates user input

* Clear cache

* Run php <path to Magento root>/bin/magento setup:config:set --help to make sure custom option is present

## Tests

Unit tests could be found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
