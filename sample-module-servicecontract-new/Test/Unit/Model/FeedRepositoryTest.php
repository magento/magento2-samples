<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Model;

use Magento\SampleServiceContractNew\API\Data\FeedSearchResultInterfaceFactory;

class FeedRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var  \Magento\SampleServiceContractNew\Model\FeedManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $feedManager;
    /**
     * @var FeedSearchResultInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchResultFactory;
    /**
     * @var  \Magento\SampleServiceContractNew\Model\FeedRepository
     */
    private $feedRepository;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->feedManager = $this->getMockBuilder('Magento\SampleServiceContractNew\Model\FeedManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->searchResultFactory = $this->getMockBuilder(
            '\Magento\SampleServiceContractNew\API\Data\FeedSearchResultInterfaceFactory'
        )
            ->disableOriginalConstructor()
            ->getMock();
        $this->feedRepository = $objectManager->getObject(
            'Magento\SampleServiceContractNew\Model\FeedRepository',
            [
                'feedManager' => $this->feedManager,
                'searchResultFactory' => $this->searchResultFactory
            ]
        );
    }

    /**
     * @dataProvider getListDataProvider
     */
    public function testGetList(array $feeds, array $filterGroups, array $expectedFilteredFeeds)
    {
        $this->feedManager->expects($this->once())
            ->method('getFeeds')
            ->willReturn($feeds);
        $searchResult = $this->getMockBuilder('\Magento\SampleServiceContractNew\API\Data\FeedSearchResultInterface')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $searchResult->expects($this->once())
            ->method('setItems')
            ->with($expectedFilteredFeeds)
            ->willReturnSelf();
        $this->searchResultFactory->expects($this->once())
            ->method('create')
            ->willReturn($searchResult);
        $searchCriteria = $this->getMockBuilder('\Magento\Framework\Api\SearchCriteria')
            ->disableOriginalConstructor()
            ->getMock();
        $searchCriteria->expects($this->once())
            ->method('getFilterGroups')
            ->willReturn($filterGroups);
        $this->assertEquals($searchResult, $this->feedRepository->getList($searchCriteria));
    }

    /**
     * @return array
     */
    public function getListDataProvider()
    {
        $feeds = [
            'empty' => $this->createFeed(null, null, null),
            'test1' => $this->createFeed('volutpat', 'Lorem ipsum dolor sit amet', 'Nam volutpat tincidunt leo quis'),
            'test2' => $this->createFeed('tincidunt', 'Nam volutpat tincidunt leo quis', 'Lorem ipsum dolor sit amet'),
            'test3' => $this->createFeed('quis', 'Nam volutpat tincidunt leo quis', 'Lorem ipsum dolor sit amet'),
            'test4' => $this->createFeed('quis', 'Nam volutpat tincidunt leo quis', 'Nam volutpat tincidunt leo quis'),
        ];


        return [
            'noFilters' => [
                'feeds' => $feeds,
                'filterGroups' => [],
                'expectedFeeds' => array_values($feeds),
            ],
            'filterById' => [
                'feeds' => $feeds,
                'filterGroups' => [$this->createFilterGroup([$this->createFilter('id', 'volutpat')])],
                'expectedFeed' => [$feeds['test1']],
            ],
            'filterByTitle' => [
                'feeds' => $feeds,
                'filterGroups' => [$this->createFilterGroup([$this->createFilter('title', 'volutpat')])],
                'expectedFeed' => [$feeds['test2'], $feeds['test3'], $feeds['test4']],
            ],
            'filterByDescription' => [
                'feeds' => $feeds,
                'filterGroups' => [$this->createFilterGroup([$this->createFilter('description', 'Nam')])],
                'expectedFeed' => [$feeds['test1'], $feeds['test4']],
            ],
            'filterByUnknownField' => [
                'feeds' => $feeds,
                'filterGroups' => [$this->createFilterGroup([$this->createFilter('comment', 'Nam')])],
                'expectedFeed' => [],
            ],
            'filterByTitleAndDescription' => [
                'feeds' => $feeds,
                'filterGroups' => [
                    $this->createFilterGroup([
                        $this->createFilter('title', 'volutpat'),
                        $this->createFilter('description', 'tincidunt')
                    ])
                ],
                'expectedFeed' => [$feeds['test4']],
            ]
        ];
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
     * @expectedException \Magento\Framework\Exception\NotFoundException
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

    private function createFilter($field, $value)
    {
        $filter = $this->getMockBuilder('\Magento\Framework\Api\Filter')
            ->disableOriginalConstructor()
            ->getMock();
        $filter->expects($this->any())
            ->method('getField')
            ->willReturn($field);
        $filter->expects($this->any())
            ->method('getValue')
            ->willReturn($value);
        return $filter;
    }

    private function createFilterGroup(array $filters)
    {
        $filterGroup = $this->getMockBuilder('\Magento\Framework\Api\Search\FilterGroup')
            ->disableOriginalConstructor()
            ->getMock();
        $filterGroup->expects($this->atLeastOnce())
            ->method('getFilters')
            ->willReturn($filters);
        return $filterGroup;
    }

    private function createFeed($id = 'fieldId', $title = 'fieldTitle', $description = 'fieldDescription')
    {
        $feed = $this->getMockBuilder('Magento\SampleServiceContractNew\API\Data\FeedInterface')
            ->disableOriginalConstructor()
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
        return $feed;
    }
}
