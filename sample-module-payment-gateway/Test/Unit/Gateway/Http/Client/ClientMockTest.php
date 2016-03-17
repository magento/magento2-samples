<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SamplePaymentProvider\Test\Unit\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use Magento\SamplePaymentGateway\Gateway\Http\Client\ClientMock;

class ClientMockTest extends \PHPUnit_Framework_TestCase
{
    const TXN_ID = 'fcd7f001e9274fdefb14bff91c799306';

    /**
     * @var Logger|\PHPUnit_Framework_MockObject_MockObject
     */
    private $logger;

    /**
     * @var ClientMock|\PHPUnit_Framework_MockObject_MockObject
     */
    private $clientMock;

    public function setUp()
    {
        $this->logger = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->clientMock = $this->getMockBuilder(ClientMock::class)
            ->setMethods(['generateTxnId'])
            ->setConstructorArgs([$this->logger])
            ->getMock();
    }

    /**
     * @param array $expectedRequest
     * @param array $expectedResponse
     * @param array $expectedHeaders
     *
     * @dataProvider placeRequestDataProvider
     */
    public function testPlaceRequest(array $expectedRequest, array $expectedResponse, array $expectedHeaders)
    {
        /** @var TransferInterface|\PHPUnit_Framework_MockObject_MockObject $transferObject */
        $transferObject = $this->getMock(TransferInterface::class);
        $transferObject->expects(static::once())
            ->method('getBody')
            ->willReturn($expectedRequest);
        $transferObject->expects(static::once())
            ->method('getHeaders')
            ->willReturn($expectedHeaders);

        $this->clientMock->expects(static::once())
            ->method('generateTxnId')
            ->willReturn(self::TXN_ID);

        $this->logger->expects(static::once())
            ->method('debug')
            ->with(
                [
                    'request' => $expectedRequest,
                    'response' => $expectedResponse
                ]
            );

        static::assertEquals(
            $expectedResponse,
            $this->clientMock->placeRequest($transferObject)
        );
    }

    /**
     * @return array
     */
    public function placeRequestDataProvider()
    {
        return [
            'success' => [
                'expectedRequest' => [
                    'TNX_TYPE' => 'A',
                    'INVOICE' => 1000
                ],
                'expectedResponse' => [
                    'RESULT_CODE' => ClientMock::SUCCESS,
                    'TXN_ID' => self::TXN_ID
                ],
                'expectedHeaders' => [
                    'force_result' => ClientMock::SUCCESS
                ]
            ],
            'fraud' => [
                'expectedRequest' => [
                    'TNX_TYPE' => 'A',
                    'INVOICE' => 1000
                ],
                'expectedResponse' => [
                    'RESULT_CODE' => ClientMock::FAILURE,
                    'TXN_ID' => self::TXN_ID,
                    'FRAUD_MSG_LIST' => [
                        'Stolen card',
                        'Customer location differs'
                    ]
                ],
                'expectedHeaders' => [
                    'force_result' => ClientMock::FAILURE
                ]
            ]
        ];
    }
}
