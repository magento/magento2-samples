<?php
namespace Magento\SampleNewPage\Controller\Index;

/**
 * Responsible for loading the extension's hello world page
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Load the page defined in view/frontend/layout/samplenewpage_index_sayhello.xml
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}