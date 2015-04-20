<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Block;

class LocationsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetColumns()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $context = $this->getMock('Magento\Backend\Block\Template\Context', [], [], '', false);

        /** @var \Magento\SampleShippingProvider\Block\System\Config\Form\Field\Locations $model */
        $model = $objectManager->getObject(
            'Magento\SampleShippingProvider\Block\System\Config\Form\Field\Locations',
            [
                'context' => $context
            ]
        );

        $this->assertArrayHasKey('title', $model->getColumns());
        $this->assertArrayHasKey('street', $model->getColumns());
        $this->assertArrayHasKey('phone', $model->getColumns());
        $this->assertArrayHasKey('message', $model->getColumns());
        $this->assertCount(4, $model->getColumns());
    }
}
