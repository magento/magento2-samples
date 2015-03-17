<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Block;

use Magento\TestFramework\Bootstrap;

class PageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $objectManager = new \Magento\TestFramework\Helper\ObjectManager($this);

        /** @var \Magento\SampleInterception\Block\Page $model */
        $model = $objectManager->getObject('Magento\SampleInterception\Block\Page');
        $this->assertInstanceOf('Magento\SampleInterception\Helper\Intercepted\ChildBefore', $model->getHelperBefore());
        $this->assertInstanceOf('Magento\SampleInterception\Helper\Intercepted\ChildAfter', $model->getHelperAfter());
        $this->assertInstanceOf('Magento\SampleInterception\Helper\Intercepted\ChildAround', $model->getHelperAround());

    }
}
