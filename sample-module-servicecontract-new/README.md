## Synopsis

This extension contains a simple implementation of multiple rss feeds.

It provides an example of API which should be used to add new RSS feed from third-party module.
Also it is provides an API to access available RSS feeds from anywhere.

The list of feeds can be accessed at the following url:

`<magento2-url>/sampleservicecontractnew`

## Motivation

To demonstrate how to provide a new service contract in Magento.

## Technical features

### API

In Magento, if module declares service contract which can be used in other modules, it provides an [API directory](API)
with contains public interfaces.

Generally API consists of two parts: public interfaces which provides access to module's data
([FeedRepositoryInterface](API/FeedRepositoryInterface.php)) and the Data API, which located in [API/Data](API/Data)
and declares interfaces which should be used as a module's data [FeedInterface](API/Data/FeedInterface.php).

The main workflow of providing new API is to create public interfaces in API directory and provide it's implementation
inside [Model](Model) directory, which should be declared as a `preference` for this API through [etc/di.xml](etc/di.xml).

### Routing

### Application area

View-related files in Magento are based on "application area." For instance, if the user browsing the website is in the 
"frontend" area - such as a customer purchasing a product - pages in the admin area cannot be loaded. The _view_ folder
contains subdirectories named by application area, which contain files only relevant to that area. This extension only
deals places content on the _frontend_, so it only has a _frontend_ directory.

### Layout files

[The route config file](etc/frontend/routes.xml) maps the frontname "sampleservicecontractnew" to this extension, 
so the application looks in the extension to find the layout file describing the page.
 
There are three routing parameters used to determine which layout file must be loaded. 
 
The general form of the url is `<magento2-url>/<extension>/<action path>/<action name>/`.

Because the page is accessed at `<magento2-url>/sampleservicecontractnew`, the routing parameters are:
  - _extension_: "sampleservicecontractnew"
  - _action path_: "index"
  - _action name_: "index"
  
The _index_ values are provided as defaults when none are explicitly provided.

The application thus looks in `<extension-root>/view/frontend/layout` for a file called `sampleservicecontractnew_index_index.xml`.

### Layouts, Blocks, Templates

The layout file defines two things: a title (in the head/title element) and a block class to fill the page with content.

There can be custom or specialized block classes for sophisticated extensions, but here we use the most basic block 
class, Magento\Framework\View\Element\Template, to handle plain html content.
 
The block's template defines exactly what that content will be. The layout file defines which module contains the 
template, and what its name is. Based on that, the application will look in `<extension-root>/view/frontend/templates` 
for `feed_list.phtml`. The block uses its <code>toHtml()</code> method to process the template file and generate output html.

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
