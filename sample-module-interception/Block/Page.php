<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleInterception\Block;

use Magento\SampleInterception\Helper\Intercepted;
use Magento\Framework\View\Element\Template\Context;


/**
 * Block class for the page that shows the demo.
 */
class Page extends \Magento\Framework\View\Element\Template
{
    /**
     * Helper method used for generating content for the page. It is intercepted by a 'before' type plugin
     *
     * @var  \Magento\SampleInterception\Helper\Intercepted\ChildBefore
     */
    protected $helperBefore;

    /**
     * Helper method used for generating content for the page. It is intercepted by an 'after' type plugin
     *
     * @var  \Magento\SampleInterception\Helper\Intercepted\ChildAfter
     */
    protected $helperAfter;

    /**
     * Helper method used for generating content for the page. It is intercepted by an 'around' type plugin
     *
     * @var  \Magento\SampleInterception\Helper\Intercepted\ChildAround
     */
    protected $helperAround;

    /**
     * @param Context $context
     * @param Intercepted\ChildBefore $helperBefore
     * @param Intercepted\ChildAfter $helperAfter
     * @param Intercepted\ChildARound $helperAround
     * @param array $data
     */
    public function __construct(
        Context $context,
        Intercepted\ChildBefore $helperBefore,
        Intercepted\ChildAfter $helperAfter,
        Intercepted\ChildARound $helperAround,
        array $data = []
    ) {
        $this->helperBefore = $helperBefore;
        $this->helperAfter = $helperAfter;
        $this->helperAround = $helperAround;

        parent::__construct($context, $data);
    }

    /**
     * @return Intercepted\ChildBefore
     */
    public function getHelperBefore()
    {
        return $this->helperBefore;
    }

    /**
     * @return Intercepted\ChildAfter
     */
    public function getHelperAfter()
    {
        return $this->helperAfter;
    }

    /**
     * @return Intercepted\ChildAround
     */
    public function getHelperAround()
    {
        return $this->helperAround;
    }
}
