# Plugin Demo

This module contains a page which can be viewed at <m2root>/plugins. That page will feature a demo of plugins being 
used to directly modify page content.

## Plugin Subject

The code being modified by the plugins is a simple capitalization method, located in \M2Demo\PluginDemo\Helper\Intercepted.
Three empty classes extend this. In order to clearly demonstrate each plugin acting in isolation from other plugins, each 
plugin is only assigned to one of the classes, and will modify the method's behavior when called through that class.

## Plugins

The format of a plugin method in Magento is (before|after|around)<NameOfModifiedMethod>. Each plugin adds wrapper tags
to the content it modifies.

### Before Plugin

\M2Demo\PluginDemo\Plugin\PluginBefore::beforeBaseMethod modifies \M2Demo\PluginDemo\Helper\Intercepted\ChildBefore::baseMethod

Wraps (before)(/before) tags around the base method's input.

### After Plugin

\M2Demo\PluginDemo\Plugin\PluginAfter::afterBaseMethod modifies \M2Demo\PluginDemo\Helper\Intercepted\ChildAfter::baseMethod

WRaps (after)(/after) tags around the base method's output.

### Around Plugin

\M2Demo\PluginDemo\Plugin\PluginAround::aroundBaseMethod modifies \M2Demo\PluginDemo\Helper\Intercepted\ChildAround::baseMethod

Wraps the input to the base method in (around: before helper)(/around: before helper) tags
Wraps the output of the base method in (around: after helper)(/around: after helper) tags

## Registering the Plugin

A plugin is registered in a module's di.xml config file, located in <module>/etc/<areacode>/di.xml, or <module>/etc/di.xml.

The format to add the plugin is:

```xml
<type name="Class\To\Modify">
            <plugin name="someUniqueName" type="Class\Containing\Plugins" sortOrder="1" />
</type>
```

The application will determine which methods to use based on method naming conventions described above.

