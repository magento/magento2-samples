<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Model;


class FeedRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\SampleServiceContractNew\Model\FeedManager|\PHPUnit_Framework_MockObject_MockObject */
    private $feedManager;
    /** @var  \Magento\SampleServiceContractNew\Model\FeedRepository */
    private $feedRepository;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->feedManager = $this->getMockBuilder('Magento\SampleServiceContractNew\Model\FeedManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->feedRepository = $objectManager->getObject(
            'Magento\SampleServiceContractNew\Model\FeedRepository',
            [
                'feedManager' => $this->feedManager,
            ]
        );
    }

    public function testGetList()
    {
        $feeds = '[testFeedsList]';
        $this->feedManager->expects($this->once())
            ->method('getFeeds')
            ->willReturn($feeds);
        $this->assertEquals($feeds, $this->feedRepository->getList());
    }

    public function testGetById()
    {
        $id = 'feedIdentifier';
        $feed = 'customFeed';
        $this->feedManager->expects($this->once())
            ->method('getFeed')
            ->with($id)
            ->willReturn($feed);
        $this->assertEquals($feed, $this->feedRepository->getById($id));
    }

    /**
     * @expectedException \Magento\Framework\App\Action\NotFoundException
     * @expectedExceptionMessage Feed feedIdentifier not found
     */
    public function testGetByIdNotFoundException()
    {
        $id = 'feedIdentifier';
        $feed = null;
        $this->feedManager->expects($this->once())
            ->method('getFeed')
            ->with($id)
            ->willReturn($feed);
        $this->feedRepository->getById($id);
    }
}
