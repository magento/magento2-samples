<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleInterception\Block;

use Magento\SampleInterception\Model\Intercepted;
use Magento\Framework\View\Element\Template\Context;


/**
 * Block class for the page that shows the demo.
 */
class Page extends \Magento\Framework\View\Element\Template
{
    /**
     * Contains method used for generating content for the page. It is intercepted by a 'before' type plugin
     *
     * @var  \Magento\SampleInterception\Model\Intercepted\ChildBefore
     */
    protected $beforeModel;

    /**
     * Contains method used for generating content for the page. It is intercepted by an 'after' type plugin
     *
     * @var  \Magento\SampleInterception\Model\Intercepted\ChildAfter
     */
    protected $afterModel;

    /**
     * Contains method used for generating content for the page. It is intercepted by an 'around' type plugin
     *
     * @var  \Magento\SampleInterception\Model\Intercepted\ChildAround
     */
    protected $aroundModel;

    /**
     * Contains method used for generating page content. Its parent is intercepted.
     *
     * @var \Magento\SampleInterception\Model\Intercepted\ChildInherit
     */
    protected $inheritModel;

    /**
     * @param Context $context
     * @param Intercepted\ChildBefore $beforeModel
     * @param Intercepted\ChildAfter $afterModel
     * @param Intercepted\ChildAround $aroundModel
     * @param Intercepted\ChildInherit $inheritModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        Intercepted\ChildBefore $beforeModel,
        Intercepted\ChildAfter $afterModel,
        Intercepted\ChildAround $aroundModel,
        Intercepted\ChildInherit $inheritModel,
        array $data = []
    ) {
        $this->beforeModel = $beforeModel;
        $this->afterModel = $afterModel;
        $this->aroundModel = $aroundModel;
        $this->inheritModel = $inheritModel;

        parent::__construct($context, $data);
    }

    /**
     * @return Intercepted\ChildBefore
     */
    public function getModelBefore()
    {
        return $this->beforeModel;
    }

    /**
     * @return Intercepted\ChildAfter
     */
    public function getModelAfter()
    {
        return $this->afterModel;
    }

    /**
     * @return Intercepted\ChildAround
     */
    public function getModelAround()
    {
        return $this->aroundModel;
    }

    /**
     * @return Intercepted\ChildInherit
     */
    public function getModelInherit()
    {
        return $this->inheritModel;
    }
}
