<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SamplePaymentGateway\Test\Unit\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Payment\Model\InfoInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\SamplePaymentGateway\Gateway\Http\Client\ClientMock;
use Magento\SamplePaymentGateway\Observer\DataAssignObserver;

class DataAssignObserverTest extends \PHPUnit_Framework_TestCase
{
    public function testExectute()
    {
        $observerContainer = $this->getMockBuilder(Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $event = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();
        $paymentMethodFacade = $this->getMock(MethodInterface::class);
        $paymentInfoModel = $this->getMock(InfoInterface::class);
        $dataObject = new DataObject(
            [
                'transaction_result' => ClientMock::SUCCESS
            ]
        );

        $observerContainer->expects(static::atLeastOnce())
            ->method('getEvent')
            ->willReturn($event);
        $event->expects(static::exactly(2))
            ->method('getDataByKey')
            ->willReturnMap(
                [
                    [AbstractDataAssignObserver::METHOD_CODE, $paymentMethodFacade],
                    [AbstractDataAssignObserver::DATA_CODE, $dataObject]
                ]
            );

        $paymentMethodFacade->expects(static::once())
            ->method('getInfoInstance')
            ->willReturn($paymentInfoModel);

        $paymentInfoModel->expects(static::once())
            ->method('setAdditionalInformation')
            ->with(
                'transaction_result',
                ClientMock::SUCCESS
            );

        $observer = new DataAssignObserver();
        $observer->execute($observerContainer);
    }
}
