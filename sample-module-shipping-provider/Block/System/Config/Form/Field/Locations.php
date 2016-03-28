<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleShippingProvider\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class Locations Backend system config array field renderer
 */
class Locations extends AbstractFieldArray
{
    /**
     * Initialise columns for 'Store Locations'
     * Label is name of field
     * Class is storefront validation action for field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->addColumn(
            'title',
            [
                'label' => __('Title'),
                'class' => 'validate-no-empty validate-alphanum-with-spaces'
            ]
        );
        $this->addColumn(
            'street',
            [
                'label' => __('Street Address'),
                'class' => 'validate-no-empty validate-alphanum-with-spaces'
            ]
        );
        $this->addColumn(
            'phone',
            [
                'label' => __('Phone Number'),
                'class' => 'validate-no-empty validate-no-empty validate-phoneStrict'
            ]
        );
        $this->addColumn(
            'message',
            [
                'label' => __('Message'),
                'class' => 'validate-no-empty'
            ]
        );
        $this->_addAfter = false;
        parent::_construct();
    }
}
