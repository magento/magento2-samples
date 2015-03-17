<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleWebFlow\Block;

class FirstPage extends \Magento\Framework\View\Element\Template
{
    public function getNextPageUrl()
    {
        return $this->_urlBuilder->getUrl('webflow/nextpage');
    }
}