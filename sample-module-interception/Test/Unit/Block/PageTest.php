<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace M2Demo\PluginDemo\Test\Unit\Block;

use Magento\TestFramework\Bootstrap;

class PageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetters()
    {
        $objectManager = new \Magento\TestFramework\Helper\ObjectManager($this);

        /** @var \M2Demo\PluginDemo\Block\Page $model */
        $model = $objectManager->getObject('M2Demo\PluginDemo\Block\Page');
        $this->assertInstanceOf('M2Demo\PluginDemo\Helper\Intercepted\ChildBefore', $model->getHelperBefore());
        $this->assertInstanceOf('M2Demo\PluginDemo\Helper\Intercepted\ChildAfter', $model->getHelperAfter());
        $this->assertInstanceOf('M2Demo\PluginDemo\Helper\Intercepted\ChildAround', $model->getHelperAround());

    }
}
