<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Model;


class SampleFeedTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\SampleServiceContractNew\Model\SampleFeed */
    private $feed;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->feed = $objectManager->getObject(
            'Magento\SampleServiceContractNew\Model\SampleFeed',
            []
        );
    }

    public function testGetTitle()
    {
        $this->assertEquals(__('Feed Title'), $this->feed->getTitle());
    }

    public function testGetDescription()
    {
        $this->assertEquals(__('Feed description'), $this->feed->getDescription());
    }
}
