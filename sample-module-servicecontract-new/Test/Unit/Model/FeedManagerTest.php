<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Model;


use Magento\SampleServiceContractNew\API\Data\FeedInterface;
use Magento\SampleServiceContractNew\Model\FeedManager;

class FeedManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $dataObjectHelper;
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    private $urlBuilder;

    protected function setUp()
    {
        $this->dataObjectHelper = $this->getMockBuilder('\Magento\Framework\Api\DataObjectHelper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->urlBuilder = $this->getMockBuilder('Magento\SampleServiceContractNew\Model\UrlBuilder')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetFeeds()
    {
        $feeds = ['id1' => $this->createFeed('id1', 'title1', 'description1', 'url1')];
        $feedManager = $this->createFeedManager($feeds);
        $this->assertEquals(array_values($feeds), $feedManager->getFeeds());
    }

    public function testGetFeed()
    {
        $feed = $this->createFeed('id2', 'title2', 'description2', 'url2');
        $feeds = ['id2' => $feed];
        $feedManager = $this->createFeedManager($feeds);
        $this->assertEquals($feed, $feedManager->getFeed('id2'));
    }

    public function testGetNotAvailableFeed()
    {
        $feed = $this->createFeed('id3', 'title3', 'description3', 'url3');
        $feeds = ['id3' => $feed];
        $feedManager = $this->createFeedManager($feeds);
        $this->assertEquals(null, $feedManager->getFeed('id25'));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FeedInterface
     */
    private function createFeed($id, $title, $description, $url)
    {
        $feed = $this->getMockBuilder('\Magento\SampleServiceContractNew\API\Data\FeedInterface')
            ->getMockForAbstractClass();
        $feed->expects($this->any())
            ->method('getId')
            ->willReturn($id);
        $feed->expects($this->any())
            ->method('getTitle')
            ->willReturn($title);
        $feed->expects($this->any())
            ->method('getDescription')
            ->willReturn($description);
        $feed->expects($this->any())
            ->method('getUrl')
            ->willReturn($url);
        return $feed;
    }

    /**
     * @param FeedInterface[] $feeds
     * @return FeedManager
     */
    private function createFeedManager(array $feeds)
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        return $objectManager->getObject(
            'Magento\SampleServiceContractNew\Model\FeedManager',
            [
                'dataObjectHelper' => $this->dataObjectHelper,
                'urlBuilder' => $this->urlBuilder,
                'feeds' => $feeds,
            ]
        );
    }
}
