<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractReplacement\Test\Unit\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\App\CacheInterface;
use Magento\SampleServiceContractReplacement\Model\ItemRepository;
use Magento\SampleServiceContractReplacement\Model\QuoteRepository;
use Magento\GiftMessage\Api\Data\MessageInterface;
use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Api\Data\CartInterface;

class ItemRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ItemRepository
     */
    protected $itemRepository;

    /**
     * @var QuoteRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteRepositoryMock;

    /**
     * @var CartItemInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteItemMock;

    /**
     * @var CartInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteMock;

    /**
     * @var CacheInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $cacheMock;

    /**
     * @var MessageInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageMock;

    /**
     * @var int
     */
    protected $cartId = 1;

    /**
     * @var int
     */
    protected $itemId = 2;

    protected function setUp()
    {
        $this->quoteItemMock = $this->getMock('Magento\Quote\Api\Data\CartItemInterface', [], [], '', false);

        /** @var \Magento\Quote\Api\CartItemRepositoryInterface $quoteItemRepository */
        $quoteItemRepository = $this->getMock('Magento\Quote\Api\CartItemRepositoryInterface', [], [], '', false);
        $quoteItemRepository->expects($this->any())
            ->method('getList')
            ->willReturn([$this->quoteItemMock]);

        $this->quoteMock = $this->getMock('Magento\Quote\Api\Data\CartInterface', [], [], '', false);
        $this->quoteRepositoryMock = $this->getMock(
            'Magento\SampleServiceContractReplacement\Model\QuoteRepository',
            ['get', 'getList'],
            [],
            '',
            false
        );

        $this->cacheMock = $this->getMock('Magento\Framework\App\CacheInterface', [], [], '', false);
        $this->messageMock = $this->getMock('Magento\GiftMessage\Api\Data\MessageInterface', [], [], '', false);

        $this->itemRepository = new ItemRepository($quoteItemRepository, $this->quoteRepositoryMock, $this->cacheMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage  There is no item with provided id in the cart
     */
    public function testGetNonExistingId()
    {
        $this->quoteItemMock->expects($this->once())->method('getItemId')->willReturn($this->itemId);
        $this->itemRepository->get(0, 0);
    }

    public function testGet()
    {
        $this->quoteItemMock->expects($this->once())->method('getItemId')->willReturn($this->itemId);
        $this->cacheMock
            ->expects($this->once())
            ->method('load')
            ->willReturn(serialize($this->messageMock));

        $giftMsg = $this->itemRepository->get($this->cartId, $this->itemId);
        $this->assertEquals($this->messageMock, $giftMsg);
        $this->assertInstanceOf('Magento\GiftMessage\Api\Data\MessageInterface', $giftMsg);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No such entity with cartId = 0
     */
    public function testSaveWithNoSuchEntityException()
    {
        $this->quoteRepositoryMock->expects($this->any())
            ->method('get')
            ->willThrowException(new NoSuchEntityException(__('No such entity with cartId = 0')));
        $this->itemRepository->save(0, $this->messageMock, 0);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage There is no item with provided id in the cart
     */
    public function testSaveWithNoSuchEntityExceptionItem()
    {
        $this->quoteRepositoryMock->expects($this->any())
            ->method('get')
            ->willReturn($this->quoteMock);
        $this->quoteItemMock->expects($this->once())->method('getItemId')->willReturn($this->itemId);
        $this->itemRepository->save(0, $this->messageMock, 0);
    }

    /**
     * @expectedException \Magento\Framework\Exception\State\InvalidTransitionException
     * @expectedExceptionMessage Gift Messages is not applicable for virtual products
     */
    public function testSaveWithInvalidTransitionException()
    {
        $this->quoteRepositoryMock->expects($this->any())
            ->method('get')
            ->willReturn($this->quoteMock);
        $this->quoteItemMock->expects($this->once())->method('getItemId')->willReturn($this->itemId);
        $this->quoteItemMock
            ->expects($this->once())
            ->method('getProductType')
            ->willReturn(\Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL);

        $this->itemRepository->save($this->cartId, $this->messageMock, $this->itemId);
    }

    public function testSave()
    {
        $this->quoteRepositoryMock->expects($this->any())
            ->method('get')
            ->willReturn($this->quoteMock);
        $this->quoteItemMock->expects($this->once())->method('getItemId')->willReturn($this->itemId);
        $this->quoteItemMock
            ->expects($this->once())
            ->method('getProductType')
            ->willReturn(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);

        $customerMock = $this->getMock('Magento\Customer\Api\Data\CustomerInterface', [], [], '', false);
        $this->quoteMock->expects($this->once())->method('getCustomer')->willReturn($customerMock);

        $this->messageMock->expects($this->any())->method('setCustomerId');
        $this->messageMock->expects($this->any())->method('setGiftMessageId');

        $this->cacheMock
            ->expects($this->once())
            ->method('save');

        $this->assertTrue($this->itemRepository->save($this->cartId, $this->messageMock, $this->itemId));
    }
}
