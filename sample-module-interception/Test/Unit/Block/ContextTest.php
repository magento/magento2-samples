<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Block;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $before = $this->getMockBuilder('Magento\SampleInterception\Helper\Intercepted\ChildBefore')
            ->disableOriginalConstructor()
            ->getMock();
        $after = $this->getMockBuilder('Magento\SampleInterception\Helper\Intercepted\ChildAfter')
            ->disableOriginalConstructor()
            ->getMock();
        $around = $this->getMockBuilder('Magento\SampleInterception\Helper\Intercepted\ChildAround')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Magento\SampleInterception\Block\Page $model */
        $model = $objectManager->getObject(
            'Magento\SampleInterception\Block\Context',
            [
                'helperBefore' => $before,
                'helperAfter' => $after,
                'helperAround' => $around
            ]
        );

        $this->assertInstanceOf('Magento\SampleInterception\Helper\Intercepted\ChildBefore', $model->getHelperBefore());
        $this->assertInstanceOf('Magento\SampleInterception\Helper\Intercepted\ChildAfter', $model->getHelperAfter());
        $this->assertInstanceOf('Magento\SampleInterception\Helper\Intercepted\ChildAround', $model->getHelperAround());

    }
}
