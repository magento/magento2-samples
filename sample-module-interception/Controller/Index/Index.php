<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Controller\Index;

/**
 * Loads the demo page.
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Load plugin demo page
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}