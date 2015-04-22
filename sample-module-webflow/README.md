## Synopsis

This extension contains a simple demonstrate of two page linked together in a flow. The example can be accessed at the following url:

`<magento2-url>/webflow/firstpage`

## Motivation

This extension demonstrate how to link from one webpage to another using Magento's Url framework. 

## Technical feature

### Url Argument

[The layout file for the first page](view/frontend/layout/webflow_firstpage_index.xml) defines the block that controls
the page's content in the <block> element. In the <block> element, <arguments> can contain multiple <argument> elements
that can be injected into the block's constructor via the $data array. See [Magento 2 Dev Docs](http://devdocs.magento.com/guides/v1.0/extension-dev-guide/depend-inj.html#dep-inj-mod-type-args)
for information about argument injection.

Because the argument name does not match any parameter names in the constructor, it goes into the data array and is 
accessed by the getData() method.

### Magento\Framework\UrlInterface

An argument of type url has a path attribute. During runtime, Magento\Framework\UrlInterface is given this path, and 
generates a full URL corresponding to it. This url is the value that gets injected.

## Installation

This module is intended to be installed using composer.  
After the code is marshalled by composer, enable the module by adding it the list of enabled modules in 
[Magento configuration](app/etc/config.php) or, if that file does not exist, installing Magento.
After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

## Tests

Unit tests are found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
