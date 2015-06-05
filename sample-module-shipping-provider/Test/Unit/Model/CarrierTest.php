<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Block;

class CarrierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\Method|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $method;

    /**
     * @var \Magento\Shipping\Model\Rate\Result|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rateResult;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfig;

    /**
     * @var \Magento\SampleShippingProvider\Model\Carrier
     */
    protected $model;

    /**
     * @var \Magento\Checkout\Model\Type\Onepage|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var array
     */
    protected $locationData = [
        'title' => 'Store 1',
        'street' => '4895 Norman Street',
        'phone' => '323-329-5126',
        'message' => 'Open hours 8:00-21:00',
    ];

    protected $shippingOrigin = [
        'country_id' => 'US',
        'region_id' => '12',
        'postcode' => '90014',
        'city' => 'Los Angeles',
    ];

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->scopeConfig = $this->getMock('\Magento\Framework\App\Config\ScopeConfigInterface', [], [], '', false);
        $errorFactory = $this->getMock('\Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory', [], [], '', false);
        $logger = $this->getMock('\Psr\Log\LoggerInterface', [], [], '', false);

        $this->rateResult = $this->getMock('\Magento\Shipping\Model\Rate\Result', [], [], '', false);
        $rateResultFactory = $this->getMock('\Magento\Shipping\Model\Rate\ResultFactory', [], [], '', false);
        $rateResultFactory->expects($this->any())->method('create')->willReturn($this->rateResult);

        $this->method = $this->getMock(
            '\Magento\Quote\Model\Quote\Address\RateResult\Method',
            ['setCarrier', 'setCarrierTitle', 'setMethodTitle', 'setMethod', 'setPrice', 'setCost'],
            [],
            '',
            false
        );
        $methodFactory = $this->getMock(
            'Magento\Quote\Model\Quote\Address\RateResult\MethodFactory',
            [],
            [],
            '',
            false
        );
        $methodFactory->expects($this->any())->method('create')->willReturn($this->method);

        $this->model = $objectManager->getObject(
            'Magento\SampleShippingProvider\Model\Carrier',
            [
                'scopeConfig' => $this->scopeConfig,
                'rateErrorFactory' => $errorFactory,
                'logger' => $logger,
                'rateResultFactory' => $rateResultFactory,
                'rateMethodFactory' => $methodFactory
            ]
        );
    }

    public function testGetAllowedMethods()
    {
        $name = 'In-Store Pickup';
        $code = 'storepickup';
        $this->scopeConfig->expects($this->once())->method('getValue')->willReturn($name);
        $methods = $this->model->getAllowedMethods();
        $this->assertArrayHasKey($code, $methods);
        $this->assertEquals($name, $methods[$code]);
    }

    public function testCollectRatesNotActive()
    {
        $this->mockIsActive(false);
        $this->assertFalse($this->model->collectRates(new \Magento\Framework\Object));
    }

    public function testCollectRates()
    {
        $this->mockIsActive(true);
        $this->mockGetLocations();
        $this->mockBuildRateForLocation();

        $this->rateResult->expects($this->once())->method('append')->with($this->method);
        $result = $this->model->collectRates(new \Magento\Framework\Object);
        $this->assertInstanceOf('Magento\Shipping\Model\Rate\Result', $result);
    }

    protected function mockIsActive($result)
    {
        $this->scopeConfig
            ->expects($this->at(0))
            ->method('getValue')
            ->with('carriers/storepickup/active')
            ->willReturn($result);
    }

    protected function mockGetLocations()
    {
        $this->scopeConfig
            ->expects($this->at(1))
            ->method('getValue')
            ->with('carriers/storepickup/store_locations')
            ->willReturn(serialize([$this->locationData]));
        $this->mockGetShippingOrigin();
    }

    protected function mockGetShippingOrigin()
    {
        $this->scopeConfig
            ->expects($this->at(2))
            ->method('getValue')
            ->willReturn($this->shippingOrigin['country_id']);
        $this->scopeConfig
            ->expects($this->at(3))
            ->method('getValue')
            ->willReturn($this->shippingOrigin['region_id']);
        $this->scopeConfig
            ->expects($this->at(4))
            ->method('getValue')
            ->willReturn($this->shippingOrigin['postcode']);
        $this->scopeConfig
            ->expects($this->at(5))
            ->method('getValue')
            ->willReturn($this->shippingOrigin['city']);
    }

    protected function mockBuildRateForLocation()
    {
        $this->method->expects($this->once())->method('setCarrier')->with('storepickup');
        $this->scopeConfig
            ->expects($this->at(6))
            ->method('getValue')
            ->willReturn('In-Store Pickup');
        $this->method->expects($this->once())->method('setCarrierTitle')->with('In-Store Pickup');
        $this->method->expects($this->once())->method('setMethodTitle');
        $this->method->expects($this->once())->method('setMethod')->with('store_1');
        $this->method->expects($this->once())->method('setPrice')->with(0);
        $this->method->expects($this->once())->method('setCost')->with(0);
    }
}
