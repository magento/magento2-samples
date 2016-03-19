<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleShippingProvider\Test\Unit\Block\System\Config\Form\Field;

use Magento\SampleShippingProvider\Block\System\Config\Form\Field\Locations;

/**
 * Class LocationsTest
 */
class LocationsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Locations
     */
    protected $block;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        /** @var \Magento\Backend\Block\Template\Context $context */
        $context = $this->getMockBuilder('Magento\Backend\Block\Template\Context')
            ->disableOriginalConstructor()
            ->getMock();

        $this->block = new Locations($context);
    }

    public function testGetColumns()
    {
        $this->assertArrayHasKey('title', $this->block->getColumns());
        $this->assertArrayHasKey('street', $this->block->getColumns());
        $this->assertArrayHasKey('phone', $this->block->getColumns());
        $this->assertArrayHasKey('message', $this->block->getColumns());
        $this->assertCount(4, $this->block->getColumns());
    }
}
