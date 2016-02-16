## Synopsis

This is a meta package that pulls in all the sample Magento 2 modules from [repo.magento.com](http://repo.magento.com/)

## Installation

To use these samples you will first need to [install Magento 2](http://devdocs.magento.com/guides/v1.0/install-gde/bk-install-guide.html).

Update the root composer.json for Magento 2 to add a dependency on this package.

This can be done by adding the following to the 'require' section.

    "magento/sample-bundle-all": "*"
    
Ensure you have the repo.magento.com added as a repository in your composer.json and then run `composer update` to have composer download the sample modules.

Once installed, run the Magento 2 setup application and enable each of the installed modules.
