<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SamplePaymentProvider\Model;

class PayinstoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\SamplePaymentProvider\Model\Payinstore
     */
    protected $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfig;

    protected function setUp()
    {
        $objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $eventManager = $this->getMock('Magento\Framework\Event\ManagerInterface', [], [], '', false);
        $paymentDataMock = $this->getMock('Magento\Payment\Helper\Data', [], [], '', false);
        $this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface', [], [], '', false);
        $this->model = $objectManagerHelper->getObject(
            'Magento\SamplePaymentProvider\Model\Payinstore',
            [
                'eventManager' => $eventManager,
                'paymentData' => $paymentDataMock,
                'scopeConfig' => $this->scopeConfig,
            ]
        );
    }

    public function testGetInfoBlockType()
    {
        $this->assertEquals('Magento\Payment\Block\Info\Instructions', $this->model->getInfoBlockType());
    }

    public function testFormBlockType()
    {
        $this->assertEquals('Magento\SamplePaymentProvider\Block\Form\Payinstore', $this->model->getFormBlockType());
    }

    public function testIsAvailableActiveAllowed()
    {
        $this->scopeConfig->expects($this->at(0))->method('getValue')->willReturn(1);
        $this->scopeConfig->expects($this->at(1))->method('getValue')->willReturn('flatrate_flatrate');

        $shippingAddress = $this->getMockBuilder('Magento\Quote\Api\Data\AddressInterface')
            ->setMethods(array('getShippingMethod'))
            ->getMockForAbstractClass();
        $shippingAddress->expects($this->once())->method('getShippingMethod')->willReturn('flatrate_flatrate');

        /** @var /PHPUnit_Framework_MockObject_MockObject $quote */
        $quote = $this->getMockBuilder('Magento\Quote\Api\Data\CartInterface')
            ->setMethods(array('getStoreId'))
            ->getMockForAbstractClass();
        $quote->expects($this->at(0))->method('__call')->willReturn(1);
        $quote->expects($this->once())->method('getShippingAddress')->willReturn($shippingAddress);

        $this->assertTrue($this->model->isAvailable($quote));
    }

    public function testIsAvailableActiveNotAllowed()
    {
        $this->scopeConfig->expects($this->at(0))->method('getValue')->willReturn(1);
        $this->scopeConfig->expects($this->at(1))->method('getValue')->willReturn('tablerate_tablerate');

        $shippingAddress = $this->getMockBuilder('Magento\Quote\Api\Data\AddressInterface')
            ->setMethods(array('getShippingMethod'))
            ->getMockForAbstractClass();
        $shippingAddress->expects($this->once())->method('getShippingMethod')->willReturn('flatrate_flatrate');

        /** @var /PHPUnit_Framework_MockObject_MockObject $quote */
        $quote = $this->getMockBuilder('Magento\Quote\Api\Data\CartInterface')
            ->setMethods(array('getStoreId'))
            ->getMockForAbstractClass();
        $quote->expects($this->at(0))->method('__call')->willReturn(1);
        $quote->expects($this->once())->method('getShippingAddress')->willReturn($shippingAddress);

        $this->assertFalse($this->model->isAvailable($quote));
    }

    public function testIsAvailableNotActive()
    {
        $this->scopeConfig->expects($this->at(0))->method('getValue')->willReturn(0);
        /** @var /PHPUnit_Framework_MockObject_MockObject $quote */
        $quote = $this->getMockBuilder('Magento\Quote\Api\Data\CartInterface')
            ->setMethods(array('getStoreId'))
            ->getMockForAbstractClass();
        $this->assertFalse($this->model->isAvailable($quote));
    }

    public function testIsAvailableNoQuote()
    {
        $this->assertFalse($this->model->isAvailable());
    }

    public function testInitialize()
    {
        $state = 'pending_payment';
        $this->scopeConfig->expects($this->at(0))->method('getValue')->willReturn($state);
        $stateObject = new \Magento\Framework\Object();
        $this->model->initialize('action', $stateObject);
        $this->assertEquals($state, $stateObject->getState());
        $this->assertEquals($state, $stateObject->getStatus());
        $this->assertFalse($stateObject->getIsNotified());
    }
}
