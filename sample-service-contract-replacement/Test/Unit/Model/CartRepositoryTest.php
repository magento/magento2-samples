<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractReplacement\Test\Unit\Model;

use Magento\SampleServiceContractReplacement\Model\CartRepository;

class CartRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @var \Magento\Quote\Api\Data\CartInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteMock;

    /**
     * @var \Magento\Framework\App\CacheInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $cacheMock;

    /**
     * @var \Magento\GiftMessage\Api\Data\MessageInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageMock;

    /**
     * @var int
     */
    protected $cartId = 1;

    protected function setUp()
    {
        $this->quoteMock = $this->getMock(
            'Magento\Quote\Api\Data\CartInterface',
            [],
            [],
            '',
            false
        );

        /** @var \Magento\Quote\Api\CartRepositoryInterface $quoteRepositoryMock */
        $quoteRepositoryMock =
            $this->getMock('Magento\Quote\Api\CartRepositoryInterface', ['get', 'getList'], [], '', false);
        $quoteRepositoryMock->expects($this->any())
            ->method('get')
            ->willReturn($this->quoteMock);

        $this->cacheMock = $this->getMock('Magento\Framework\App\CacheInterface', [], [], '', false);
        $this->messageMock = $this->getMock('Magento\GiftMessage\Api\Data\MessageInterface', [], [], '', false);

        $this->cartRepository = new CartRepository($quoteRepositoryMock, $this->cacheMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No such entity with cartId = 0
     */
    public function testGetNonExistingId()
    {
        $this->quoteMock->expects($this->at(0))->method('getIsActive')->willReturn(false);
        $this->cartRepository->get(0);
    }

    public function testGet()
    {
        $this->quoteMock->expects($this->at(0))->method('getIsActive')->willReturn(true);
        $this->cacheMock
            ->expects($this->once())
            ->method('load')
            ->willReturn(serialize($this->messageMock));

        $giftMsg = $this->cartRepository->get($this->cartId);
        $this->assertEquals($this->messageMock, $giftMsg);
        $this->assertInstanceOf('Magento\GiftMessage\Api\Data\MessageInterface', $giftMsg);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage No such entity with cartId = 1
     */
    public function testSaveWithNoSuchEntityException()
    {
        $this->quoteMock->expects($this->once())->method('getIsActive')->willReturn(false);
        $this->cartRepository->save($this->cartId, $this->messageMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage Gift Messages is not applicable for empty cart
     */
    public function testSaveWithInputException()
    {
        $this->quoteMock->expects($this->at(0))->method('getIsActive')->willReturn(true);
        $this->quoteMock->expects($this->at(1))->method('getItemsCount')->willReturn(0);
        $this->cartRepository->save($this->cartId, $this->messageMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\State\InvalidTransitionException
     * @expectedExceptionMessage Gift Messages is not applicable for virtual products
     */
    public function testSaveWithInvalidTransitionException()
    {
        $this->quoteMock->expects($this->at(0))->method('getIsActive')->willReturn(true);
        $this->quoteMock->expects($this->at(1))->method('getItemsCount')->willReturn(1);
        $this->quoteMock->expects($this->once())->method('getIsVirtual')->willReturn(true);

        $this->cartRepository->save($this->cartId, $this->messageMock);
    }

    public function testSave()
    {
        $this->quoteMock->expects($this->at(0))->method('getIsActive')->willReturn(true);
        $this->quoteMock->expects($this->at(1))->method('getItemsCount')->willReturn(1);
        $this->quoteMock->expects($this->once())->method('getIsVirtual')->willReturn(false);

        $customerMock = $this->getMock('Magento\Customer\Api\Data\CustomerInterface', [], [], '', false);
        $this->quoteMock->expects($this->once())->method('getCustomer')->willReturn($customerMock);

        $this->messageMock->expects($this->any())->method('setCustomerId');
        $this->messageMock->expects($this->any())->method('setGiftMessageId');

        $this->cacheMock
            ->expects($this->once())
            ->method('save');

        $this->assertTrue($this->cartRepository->save($this->cartId, $this->messageMock));
    }
}


