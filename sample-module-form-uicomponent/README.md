## Synopsis

This extension contains page that can be accessed at the following url:

`<magento2-admin-url>/sampleform`

and contains Form UI Component with customized field

## Motivation

To demonstrate how to configure Form UI Component on page and process custom fields.
The backend support for saving and filling form data is out of scope of this example

## Technical features

### Layout files

The layout file can configure:
- title (in the head/title element)
- block classes to fill the page with content
- templates
- UI Component instances

In this example,
sampleform_index_index.xml layout file references an instance of Form UI Component like this:

    <uiComponent name="sampleform_form"/>

Cause layout file represents adminhtml area page,
'sampleform_form' instance configuration will be searched for in '<extension-root>/view/adminhtml/ui_component' folder

### UI Component instance

The UI Component instances can be configured for particular module,
by placing its configuration in '<module path>/view/<area>/ui_component/'.

So here Form UI Component instance named 'sampleform_form' is configured in the '<extension-root>/view/adminhtml/ui_component/sampleform_form.xml'
and contains nodes:

- argument (standard configuration node, common for all UI components)
- datasource (child component)
- fieldset (child component)
(note, child components allowed for the component are configured in 'lib\internal\Magento\Framework\Ui\etc\ui_definition.xsd')

'argument' node is extending existing base configuration for Form Component, that can be found in 'code\Magento\Ui\view\base\ui_component\etc\definition.xml'
It references data provider for this form instance:
    <item name="provider" xsi:type="string">sampleform_form.sampleform_form_data_source</item>

'dataSource' node configures data provider for this form instance.
It's 'class' argument references class that will provide the collection of data for this particular form,
and 'primaryFieldName' is the name of unique identifier field in this collection.
In this example, data provider class is just a placeholder and returns empty array,
so form inputs will be initially empty.

'fieldset' node contains set of 'field', each with 'input' formElement configuration option.
One of the fields will be standard,
while another will have customized template and js constructor

### UI Component js constructor and template
Each UI Component configuration can override default options (that are defined in 'code\Magento\Ui\view\base\ui_component\etc\definition.xml')
Overriding template will give it different look,
and overriding js constructor will change it behaviour.

In this example,
the field named 'color' explicitly defines constructor and element template for the formElement:
    <item name="component" xsi:type="string">Magento_SampleForm/js/form/element/colorSelect</item>
    <item name="elementTmpl" xsi:type="string">Magento_SampleForm/form/element/colorSelect</item>
thus overriding the default ones of the 'input' component from the definition.xml.

Note, that "template" is still input's default (that is defined in
'code\Magento\Ui\view\base\ui_component\etc\definition.xml'
in  <input ..>..<item name="template" xsi:type="string">ui/form/field</item>)
And inside of the default input's template the "elementTemplate" is rendered
(Somewhere inside of the field.html: <!-- ko template: element.elementTmpl --><!-- /ko -->)
The deafault template is useful as it renders field's label, and contains base validation,
if it is configured for the field.

Now, the Field UI Component's backend class 'code\Magento\Ui\Component\Form\Field.php' will use these new options,
when create configuration of the component.

Note, that new constructor is derived from the Abstract: it is base for the Form Elements,
as contains base validation and set observables ('value' etc).

### Form UI Component and data provider

Documentation about the Form UI Component: http://devdocs.magento.com/guides/v2.0/ui-library/ui-form.html

## Installation

This module is intended to be installed
a)using composer
or
b)just copying:
  - Clone the magento2-samples repository
  - Create a directory for the sample module: <your Magento install dir>/app/code/Magento/SampleForm
  - Copy the contents of <magento2-samples clone dir>/sample-form-ui to the <your Magento install dir>/app/code/Magento/SampleForm

Then, install or update Magento.
This module does not require to update DB.

After including this component and enabling it, you can verify it is installed by going the backend at:
STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output
Once there check that the module name shows up in the list to confirm that it was installed correctly.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
