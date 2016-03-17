<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Model\Data;


use Magento\SampleServiceContractNew\API\Data\FeedInterface;

class FeedTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\SampleServiceContractNew\Model\Data\Feed */
    private $feed;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->feed = $objectManager->getObject(
            'Magento\SampleServiceContractNew\Model\Data\Feed',
            [
                'data' => [
                    FeedInterface::KEY_ID => 'feedId',
                    FeedInterface::KEY_TITLE => 'feedTitle',
                    FeedInterface::KEY_DESCRIPTION => 'feedDescription',
                    FeedInterface::KEY_LINK => 'feedLink',
                ]
            ]
        );
    }

    public function testGetId()
    {
        $this->assertEquals('feedId', $this->feed->getId());
    }

    public function testGetTitle()
    {
        $this->assertEquals('feedTitle', $this->feed->getTitle());
    }

    public function testGetDescription()
    {
        $this->assertEquals('feedDescription', $this->feed->getDescription());
    }

    public function testGetLink()
    {
        $this->assertEquals('feedLink', $this->feed->getLink());
    }

    public function testSetLink()
    {
        $this->feed->setLink('feedTestLink');
        $this->assertEquals('feedTestLink', $this->feed->getLink());
    }
}
