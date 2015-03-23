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

        $before = $this->getMock('Magento\SampleInterception\Model\Intercepted\ChildBefore');
        $after = $this->getMock('Magento\SampleInterception\Model\Intercepted\ChildAfter');
        $around = $this->getMock('Magento\SampleInterception\Model\Intercepted\ChildAround');


        /** @var \Magento\SampleInterception\Block\Page $model */
        $model = $objectManager->getObject(
            'Magento\SampleInterception\Block\Page',
            [
                'beforeModel' => $before,
                'afterModel' => $after,
                'aroundModel' => $around
            ]
        );
        $this->assertSame($before, $model->getModelBefore());
        $this->assertSame($after, $model->getModelAfter());
        $this->assertSame($around, $model->getModelAround());

    }
}
