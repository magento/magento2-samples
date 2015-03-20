<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Block;

class PageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $helperBefore = $this->getMock('Magento\SampleInterception\Helper\Intercepted\ChildBefore');
        $helperAfter = $this->getMock('Magento\SampleInterception\Helper\Intercepted\ChildAfter');
        $helperAround = $this->getMock('Magento\SampleInterception\Helper\Intercepted\ChildAround');


        /** @var \Magento\SampleInterception\Block\Page $model */
        $model = $objectManager->getObject(
            'Magento\SampleInterception\Block\Page',
            [
                'helperBefore' => $helperBefore,
                'helperAfter' => $helperAfter,
                'helperAround' => $helperAround
            ]
        );
        $this->assertSame($helperBefore, $model->getHelperBefore());
        $this->assertSame($helperAfter, $model->getHelperAfter());
        $this->assertSame($helperAround, $model->getHelperAround());

    }
}
