<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SamplePaymentGateway\Test\Unit\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Model\Order\Payment;
use Magento\SamplePaymentGateway\Gateway\Response\TxnIdHandler;

class TxnIdHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandle()
    {
        $response = [
            TxnIdHandler::TXN_ID => ['fcd7f001e9274fdefb14bff91c799306']
        ];

        $paymentDO = $this->getMock(PaymentDataObjectInterface::class);
        $paymentModel = $this->getMockBuilder(Payment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $paymentDO->expects(static::once())
            ->method('getPayment')
            ->willReturn($paymentModel);


        $paymentModel->expects(static::once())
            ->method('setTransactionId')
            ->with($response[TxnIdHandler::TXN_ID]);
        $paymentModel->expects(static::once())
            ->method('setIsTransactionClosed')
            ->with(false);

        $request = new TxnIdHandler();
        $request->handle(['payment' => $paymentDO], $response);
    }
}
