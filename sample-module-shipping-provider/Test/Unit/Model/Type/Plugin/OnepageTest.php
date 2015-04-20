<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Block;

class OnepageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    protected $locationData = [
        'country_id' => 'US',
        'region_id' => '12',
        'postcode' => '90014',
        'city' => 'Los Angeles',
        'street' => '4895 Norman Street',
        'phone' => '323-329-5126',
    ];

    /**
     * @var \Magento\SampleShippingProvider\Model\Type\Plugin\Onepage
     */
    protected $model;

    /**
     * @var \Magento\Checkout\Model\Type\Onepage|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var \Magento\Quote\Model\Quote\Address|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $shippingAddress;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $carrier = $this->getMock('Magento\SampleShippingProvider\Model\Carrier', [], [], '', false);
        $carrier->expects($this->once())->method('getCarrierCode')->willReturn('storepickup');
        $carrier->expects($this->any())->method('getLocationInfo')->willReturn($this->locationData);

        $this->shippingAddress = $this->getMock(
            'Magento\Quote\Model\Quote\Address',
            ['getShippingMethod', 'setCountryId', 'setRegionId', 'setPostcode', 'setCity', 'setStreet', 'setTelephone'],
            [],
            '',
            false
        );

        $quote = $this->getMock('Magento\Quote\Model\Quote', [], [], '', false);
        $quote->expects($this->once())->method('getShippingAddress')->willReturn($this->shippingAddress);

        $this->subject = $this->getMock('Magento\Checkout\Model\Type\Onepage', [], [], '', false);
        $this->subject->expects($this->once())->method('getQuote')->willReturn($quote);

        $this->model = $objectManager->getObject(
            'Magento\SampleShippingProvider\Model\Type\Plugin\Onepage',
            [
                'carrier' => $carrier
            ]
        );

    }

    public function testAfterSaveShippingMethodFlatrate()
    {
        $this->shippingAddress->expects($this->once())->method('getShippingMethod')->willReturn('flatrate_flatrate');
        $this->shippingAddress->expects($this->never())->method('setCountryId');
        $this->model->afterSaveShippingMethod($this->subject, []);
    }

    public function testAfterSaveShippingMethod()
    {
        $this->shippingAddress->expects($this->once())->method('getShippingMethod')->willReturn('storepickup_store_1');
        $this->shippingAddress->expects($this->once())->method('setCountryId')->with($this->locationData['country_id']);
        $this->shippingAddress->expects($this->once())->method('setRegionId')->with($this->locationData['region_id']);
        $this->shippingAddress->expects($this->once())->method('setPostcode')->with($this->locationData['postcode']);
        $this->shippingAddress->expects($this->once())->method('setCity')->with($this->locationData['city']);
        $this->shippingAddress->expects($this->once())->method('setStreet')->with($this->locationData['street']);
        $this->shippingAddress->expects($this->once())->method('setTelephone')->with($this->locationData['phone']);
        $this->model->afterSaveShippingMethod($this->subject, []);
    }
}
