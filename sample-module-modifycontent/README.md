## Synopsis

This extension modifies product page. It shows how to change page layout (from `1column` to `2column-right`)
and how to replace part of the page (Block) with new template.

## Motivation

To demonstrate how to customize a webpage in Magento.

## Technical features

### Application area

View-related files in Magento are based on "application area." For instance, if the user browsing the website is in the 
"frontend" area - such as a customer purchasing a product - pages in the admin area cannot be loaded. The _view_ folder
contains subdirectories named by application area, which contain files only relevant to that area. This extension only
deals places content on the _frontend_, so it only has a _frontend_ directory.

### Layout files

In this example we want to modify product page.

Catalog module renders product view with controller `Catalog/Product/View`,
so the name of layout for this page should be `catalog_product_view.xml`.

### Layouts, Templates

[The layout file](view/frontend/layout/catalog_product_view.xml) defines two things:
change the page layout to `2columns-right` (Catalog module declares `1column` layout for this page)
and replace container `product.info.media` with custom one.

There can be custom or specialized block classes for sophisticated extensions, but here we use the most basic block 
class, Magento\Framework\View\Element\Template, to handle plain html content.
 
The block's template defines exactly what that content will be. The layout file defines which module contains the 
template, and what its name is. Based on that, the application will look in `<extension-root>/view/frontend/templates` 
for `catalog_product_view_image.phtml`. The block uses its <code>toHtml()</code> method to process the template file and generate output html.

## Installation

This module is intended to be installed using composer.  
After the code is marshalled by composer, enable the module by adding it the list of enabled modules in [the config](app/etc/config.php)
or, if that file does not exist, installing Magento.
After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

If you already have generated static files, you need to re-generate it to copy custom images from module to publicly accessible static data.

## Tests

This module doesn't contains code that can be covered with tests.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
