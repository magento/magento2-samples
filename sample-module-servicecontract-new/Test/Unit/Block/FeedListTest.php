<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Block;


class FeedListTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\SampleServiceContractNew\API\FeedRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $feedRepository;
    /** @var  \Magento\SampleServiceContractNew\Block\FeedList */
    private $block;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->feedRepository = $this->getMockBuilder('Magento\SampleServiceContractNew\API\FeedRepositoryInterface')
            ->getMockForAbstractClass();
        $this->block = $objectManager->getObject(
            'Magento\SampleServiceContractNew\Block\FeedList',
            [
                'feedRepository' => $this->feedRepository,
            ]
        );
    }

    public function testGetFeeds()
    {
        $feeds = 'testFeedsList';
        $this->feedRepository->expects($this->once())
            ->method('getList')
            ->willReturn($feeds);
        $this->assertEquals($feeds, $this->block->getFeeds());
    }
}
