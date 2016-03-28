<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractReplacement\Test\Unit\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\SampleServiceContractReplacement\Model\QuoteRepository;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Resource\Quote\Collection;
use Magento\Quote\Api\Data\CartSearchResultsInterfaceFactory;

class QuoteRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QuoteRepository
     */
    protected $model;

    /**
     * @var QuoteFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteFactoryMock;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var Store|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeMock;

    /**
     * @var Quote|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteMock;

    /**
     * @var CartSearchResultsInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $searchResultsDataFactory;

    /**
     * @var Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteCollectionMock;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->quoteFactoryMock = $this->getMock('\Magento\Quote\Model\QuoteFactory', ['create'], [], '', false);
        $this->storeManagerMock = $this->getMock('\Magento\Store\Model\StoreManagerInterface');
        $this->quoteMock = $this->getMock(
            '\Magento\Quote\Model\Quote',
            ['load', 'loadByCustomer', 'getIsActive', 'getId', '__wakeup', 'setSharedStoreIds', 'save', 'delete',
                'getCustomerId'],
            [],
            '',
            false
        );
        $this->storeMock = $this->getMock('\Magento\Store\Model\Store', [], [], '', false);
        $this->searchResultsDataFactory = $this->getMock(
            '\Magento\Quote\Api\Data\CartSearchResultsInterfaceFactory',
            ['create'],
            [],
            '',
            false
        );

        $this->quoteCollectionMock = $this->getMock('Magento\Quote\Model\Resource\Quote\Collection', [], [], '', false);

        $this->model = $objectManager->getObject(
            'Magento\SampleServiceContractReplacement\Model\QuoteRepository',
            [
                'quoteFactory' => $this->quoteFactoryMock,
                'storeManager' => $this->storeManagerMock,
                'searchResultsDataFactory' => $this->searchResultsDataFactory,
                'quoteCollection' => $this->quoteCollectionMock,
            ]
        );
    }

    public function testGet()
    {
        $cartId = 15;

        $this->quoteFactoryMock->expects($this->once())->method('create')->willReturn($this->quoteMock);
        $this->storeManagerMock->expects($this->once())->method('getStore')->willReturn($this->storeMock);
        $this->storeMock->expects($this->once())->method('getId')->willReturn($this->storeMock);
        $this->quoteMock->expects($this->never())->method('setSharedStoreIds');
        $this->quoteMock->expects($this->once())
            ->method('load')
            ->with($cartId)
            ->willReturn($this->storeMock);
        $this->quoteMock->expects($this->once())->method('getId')->willReturn($cartId);
        $this->quoteMock->expects($this->exactly(2))->method('getIsActive')->willReturn(1);

        $this->assertEquals($this->quoteMock, $this->model->get($cartId));
        $this->assertEquals($this->quoteMock, $this->model->get($cartId));
    }

    /**
     * @expectedExcpetion \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No such entity with cartId = 14
     */
    public function testGetWithExceptionById()
    {
        $cartId = 14;

        $this->quoteFactoryMock->expects($this->once())->method('create')->willReturn($this->quoteMock);
        $this->storeManagerMock->expects($this->once())->method('getStore')->willReturn($this->storeMock);
        $this->storeMock->expects($this->once())->method('getId')->willReturn($this->storeMock);
        $this->quoteMock->expects($this->never())->method('setSharedStoreIds');
        $this->quoteMock->expects($this->once())
            ->method('load')
            ->with($cartId)
            ->willReturn($this->storeMock);
        $this->quoteMock->expects($this->once())->method('getId')->willReturn(false);
        $this->quoteMock->expects($this->never())->method('getIsActive');

        $this->model->get($cartId);
    }

    /**
     * @expectedExcpetion \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No such entity with cartId = 15
     */
    public function testGetWithExceptionByIsActive()
    {
        $cartId = 15;

        $this->quoteFactoryMock->expects($this->once())->method('create')->willReturn($this->quoteMock);
        $this->storeManagerMock->expects($this->once())->method('getStore')->willReturn($this->storeMock);
        $this->storeMock->expects($this->once())->method('getId')->willReturn($this->storeMock);
        $this->quoteMock->expects($this->never())->method('setSharedStoreIds');
        $this->quoteMock->expects($this->once())
            ->method('load')
            ->with($cartId)
            ->willReturn($this->storeMock);
        $this->quoteMock->expects($this->once())->method('getId')->willReturn($cartId);
        $this->quoteMock->expects($this->once())->method('getIsActive')->willReturn(0);

        $this->model->get($cartId);
    }
}
