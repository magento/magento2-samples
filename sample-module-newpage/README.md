## Synopsis

This extension contains a new page that can be accessed at the following url:

`<magento2-url>/newpage`

## Motivation

To demonstrate how to create a custom webpage in Magento.

## Technical features

### Routing

### Application area

View-related files in Magento are based on "application area." For instance, if the user browsing the website is in the 
"frontend" area - such as a customer purchasing a product - pages in the admin area cannot be loaded. The _view_ folder
contains subdirectories named by application area, which contain files only relevant to that area. This extension only
deals places content on the _frontend_, so it only has a _frontend_ directory.

### Layout files

[The route config file](etc/frontend/routes.xml) maps the frontname "newpage" to this extension, so the application 
looks in the extension to find the layout file describing the page.
 
There are three routing parameters used to determine which layout file must be loaded. 
 
The general form of the url is `<magento2-url>/<extension>/<action path>/<action name>/`.

Because the page is accessed at `<magento2-url>/newpage`, the routing parameters are:
  - _extension_: "newpage"
  - _action path_: "index"
  - _action name_: "index"
  
The _index_ values are provided as defaults when none are explicitly provided.

The application thus looks in `<extension-root>/view/frontend/layout` for a file called `newpage_index_index.xml`.

### Layouts, Blocks, Templates

The layout file defines two things: a title (in the head/title element) and a block class to fill the page with content.

There can be custom or specialized block classes for sophisticated extensions, but here we use the most basic block 
class, Magento\Framework\View\Element\Template, to handle plain html content.
 
The block's template defines exactly what that content will be. The layout file defines which module contains the 
template, and what its name is. Based on that, the application will look in `<extension-root>/view/frontend/templates` 
for `main.phtml`. The block uses its <code>toHtml()</code> method to process the template file and generate output html.

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
