<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleWebapiClient\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Loads the demo page.
 *
 * This is a basic controller that only loads the corresponding layout file. It may duplicate other such 
 * controllers, and thus it is considered tech debt. This code duplication will be resolved in future releases.
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return Page
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
