<?php
namespace M2Demo\PluginDemo\Controller\Index;

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