## Synopsis

An extension to add Payment method.
This payment method can be restricted to work only with specific Shipping method.

## Motivation

This is one of a collection of examples to demonstrate the features of Magento 2.  The intent of this sample is to demonstrate how to create own Payment extension

## Technical feature

[system.xml](etc/adminhtml/system.xml) makes our module configurable in the admin panel.
[Configuration](etc/config.xml) 'registers' [Payinstore model](Model/Payinstore.php) as a payment method.
[Payinstore class](Model/Payinstore.php) extends AbstractMethod. This class is used to set new order state to Pending Payment.
Virtual class Magento\SamplePaymentProvider\Block\Form\Payinstore declared in [di.xml](etc/di.xml) along [template](view/frontend/templates/form/payinstore.phtml) used to display Payment Instructions.

## Installation

This module is intended to be installed using composer.  After including this component and enabling it, you can verify it is installed by going the backend at:

STORES -> Configuration -> ADVANCED/Advanced ->  Disable Modules Output

Once there check that the module name shows up in the list to confirm that it was installed correctly.

## Tests

Unit tests could be found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)
