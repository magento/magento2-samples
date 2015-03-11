<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace M2Demo\PluginDemo\Test\Unit\Block;

use Magento\TestFramework\Bootstrap;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $objectManager = new \Magento\TestFramework\Helper\ObjectManager($this);

        $before = $this->getMockBuilder('M2Demo\PluginDemo\Helper\Intercepted\ChildBefore')
            ->disableOriginalConstructor()
            ->getMock();
        $after = $this->getMockBuilder('M2Demo\PluginDemo\Helper\Intercepted\ChildAfter')
            ->disableOriginalConstructor()
            ->getMock();
        $around = $this->getMockBuilder('M2Demo\PluginDemo\Helper\Intercepted\ChildAround')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \M2Demo\PluginDemo\Block\Page $model */
        $model = $objectManager->getObject(
            'M2Demo\PluginDemo\Block\Context',
            [
                'helperBefore' => $before,
                'helperAfter' => $after,
                'helperAround' => $around
            ]
        );

        $this->assertInstanceOf('M2Demo\PluginDemo\Helper\Intercepted\ChildBefore', $model->getHelperBefore());
        $this->assertInstanceOf('M2Demo\PluginDemo\Helper\Intercepted\ChildAfter', $model->getHelperAfter());
        $this->assertInstanceOf('M2Demo\PluginDemo\Helper\Intercepted\ChildAround', $model->getHelperAround());

    }
}
