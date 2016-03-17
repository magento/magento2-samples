<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SamplePaymentProvider\Test\Unit\Gateway\Http;

use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\SamplePaymentGateway\Gateway\Http\TransferFactory;
use Magento\SamplePaymentGateway\Gateway\Request\MockDataRequest;

class TransferFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $request = [
            'parameter' => 'value',
            MockDataRequest::FORCE_RESULT => 1
        ];

        $transferBuilder = $this->getMockBuilder(TransferBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $transferObject = $this->getMock(TransferInterface::class);

        $transferBuilder->expects(static::once())
            ->method('setBody')
            ->with($request)
            ->willReturnSelf();
        $transferBuilder->expects(static::once())
            ->method('setMethod')
            ->with('POST')
            ->willReturnSelf();
        $transferBuilder->expects(static::once())
            ->method('setHeaders')
            ->with(
                [
                    'force_result' => 1
                ]
            )
            ->willReturnSelf();

        $transferBuilder->expects(static::once())
            ->method('build')
            ->willReturn($transferObject);

        $transferFactory = new TransferFactory($transferBuilder);

        static::assertSame(
            $transferObject,
            $transferFactory->create($request)
        );
    }
}
