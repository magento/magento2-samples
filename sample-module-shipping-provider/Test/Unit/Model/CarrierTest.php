<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleShippingProvider\Test\Unit\Model;

use Magento\SampleShippingProvider\Model\Carrier;

/**
 * Class CarrierTest
 */
class CarrierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Carrier
     */
    protected $model;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Shipping\Model\Rate\Result|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rateResultFactory;

    /**
     * @var \Magento\Shipping\Model\Rate\Result|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rateResult;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\Method|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rateMethodFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\Method|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rateResultMethod;

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

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->scopeConfig = $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')
            ->getMockForAbstractClass();
        $rateErrorFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')
            ->getMockForAbstractClass();
        $this->rateResultFactory = $this->getMockBuilder('Magento\Shipping\Model\Rate\ResultFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->rateResult = $this->getMockBuilder('Magento\Shipping\Model\Rate\Result')
            ->disableOriginalConstructor()
            ->getMock();
        $this->rateMethodFactory = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\MethodFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->rateResultMethod = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateResult\Method')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rateResultFactory->expects($this->any())->method('create')->willReturn($this->rateResult);
        $this->rateMethodFactory->expects($this->any())->method('create')->willReturn($this->rateResultMethod);

        $this->model = new Carrier(
            $this->scopeConfig,
            $rateErrorFactory,
            $logger,
            $this->rateResultFactory,
            $this->rateMethodFactory
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
        /** @var \Magento\Quote\Model\Quote\Address\RateRequest $request */
        $request = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockIsActive(false);
        $this->assertFalse($this->model->collectRates($request));
    }

    public function testCollectRates()
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateRequest $request */
        $request = $this->getMockBuilder('Magento\Quote\Model\Quote\Address\RateRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockIsActive(true);
        $this->mockGetLocations();
        $this->mockBuildRateForLocation();

        $this->rateResult->expects($this->once())->method('append')->with($this->rateResultMethod);

        $result = $this->model->collectRates($request);
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
        $this->rateResultMethod->expects($this->at(0))->method('setData')->with('carrier', 'storepickup');
        $this->scopeConfig
            ->expects($this->at(6))
            ->method('getValue')
            ->willReturn('In-Store Pickup');
        $this->rateResultMethod->expects($this->at(1))->method('setData')->with('carrier_title', 'In-Store Pickup');
        $this->rateResultMethod->expects($this->at(2))->method('setData')
            ->with('method_title', '4895 Norman Street, Los Angeles, US, 90014 (Open hours 8:00-21:00)');
        $this->rateResultMethod->expects($this->at(3))->method('setData')->with('method', 'store_1');
        $this->rateResultMethod->expects($this->once())->method('setPrice')->with(10);
        $this->rateResultMethod->expects($this->at(5))->method('setData')->with('cost', 10);
    }
}
