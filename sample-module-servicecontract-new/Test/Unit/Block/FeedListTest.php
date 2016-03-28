<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Block;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\SampleServiceContractNew\API\FeedRepositoryInterface;
use Magento\SampleServiceContractNew\Block\FeedList;

class FeedListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SearchCriteriaBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaBuilder;
    /**
     * @var  FeedRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $feedRepository;
    /**
     * @var  FeedList
     */
    private $block;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->feedRepository = $this->getMockBuilder('Magento\SampleServiceContractNew\API\FeedRepositoryInterface')
            ->getMockForAbstractClass();
        $this->searchCriteriaBuilder = $this->getMockBuilder('\Magento\Framework\Api\SearchCriteriaBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->block = $objectManager->getObject(
            'Magento\SampleServiceContractNew\Block\FeedList',
            [
                'feedRepository' => $this->feedRepository,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilder,
            ]
        );
    }

    public function testGetFeeds()
    {
        $feeds = ['feed1', 'feed2'];
        $searchCriteria = $this->getMockBuilder('\Magento\Framework\Api\SearchCriteria')
            ->disableOriginalConstructor()
            ->getMock();
        $this->searchCriteriaBuilder->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);
        $searchResult = $this->getMockBuilder('\Magento\SampleServiceContractNew\API\Data\FeedSearchResultInterface')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $searchResult->expects($this->once())
            ->method('getItems')
            ->willReturn($feeds);
        $this->feedRepository->expects($this->once())
            ->method('getList')
            ->willReturn($searchResult);
        $this->assertEquals($feeds, $this->block->getFeeds());
    }
}
