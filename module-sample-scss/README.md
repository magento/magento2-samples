## Synopsis

An extension to add alternative processor for source files.
This source processor works only with SCSS source files and may used as example.

## Motivation

This is one of a collection of examples to demonstrate the features of Magento 2.  The intent of this sample is to demonstrate how to create own processor SCSS source files

## Technical feature

[Magento\SampleScss\Preprocessor\Adapter\Scss\Processor](Preprocessor/Adapter/Scss/Processor.php) Adapter for compilator SCSS source files
[di.xml](etc/di.xml) Override based processor of LESS source files. Set sort order for processors resource files (directive **after**)

Run **php bin/magento setup:static-content:deploy** command and view files in the folder **pub/static/frontend/Magento/\<them-name\>/en_US/\<module-name\>/css**.
CSS file is generated from test SCSS file.

## Installation

This module is intended to be installed using composer.

## Tests

Unit tests could be found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
