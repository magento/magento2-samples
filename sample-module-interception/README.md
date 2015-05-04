## Synopsis

This module contains a page which can be viewed at [m2root]/sampleinterception. This page features a demo of plugins being 
used to directly modify page content.

## Motivation

The intent of this sample is to demonstrate the following:

1. Conventions for writing a plugin
2. Integration of plugins with other code
3. Behavior of different plugin types

## Technical features

### Plugin Types

The format of a plugin method in Magento is (before|after|around)NameOfModifiedMethod. Each plugin adds wrapper tags
to the content it modifies. The following three plugins demonstrate these different types.

The code being modified is a simple capitalization method, located in \Magento\SampleInterception\Model\Intercepted.
Several empty classes extend this. In order to clearly demonstrate each plugin acting in isolation from other plugins, each 
plugin is only assigned to one of the classes, and will modify the method's behavior when called through that class.

#### Before Plugin

\Magento\SampleInterception\Plugin\PluginBefore::beforeBaseMethod modifies \Magento\SampleInterception\Model\Intercepted\ChildBefore::baseMethod

Wraps (before)(/before) tags around the base method's input.

#### After Plugin

\Magento\SampleInterception\Plugin\PluginAfter::afterBaseMethod modifies \Magento\SampleInterception\Model\Intercepted\ChildAfter::baseMethod

Wraps (after)(/after) tags around the base method's output.

#### Around Plugin

\Magento\SampleInterception\Plugin\PluginAround::aroundBaseMethod modifies \Magento\SampleInterception\Model\Intercepted\ChildAround::baseMethod

Wraps the input to the base method in (around: before base method)(/around: before base method) tags
Wraps the output of the base method in (around: after base method)(/around: after base method) tags

### Inheritance and Plugins

The last plugin demonstrates the ability to define a plugin on a parent class, and have it modify anything that extends
that class. The class [Intercepted](Model\Intercepted) has one plugin registered to it, but that plugin 
is activated when the method is called through the [ChildInherit](Model\Intercepted\ChildInherit).

### Registering the Plugin

A plugin is registered in a module's di.xml config file, located in [module]/etc/[areacode]/di.xml, or [module]/etc/di.xml.

The format to add the plugin is:

```xml
<type name="Class\To\Modify">
    <plugin name="someUniqueName" type="Class\Containing\Plugins" sortOrder="1" />
</type>
```

The application will determine which methods to use based on method naming conventions described above.


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
