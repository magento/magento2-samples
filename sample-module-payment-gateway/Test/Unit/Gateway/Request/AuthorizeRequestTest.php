<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SamplePaymentGateway\Test\Unit\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\AddressAdapterInterface;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\SamplePaymentGateway\Gateway\Request\AuthorizationRequest;

class AuthorizeRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $merchantToken = 'secure_token';
        $invoiceId = 1001;
        $grandTotal = 12.2;
        $currencyCode = 'USD';
        $storeId = 1;
        $email = 'user@domain.com';

        $expectation = [
            'TXN_TYPE' => 'A',
            'INVOICE' => $invoiceId,
            'AMOUNT' => $grandTotal,
            'CURRENCY' => $currencyCode,
            'EMAIL' => $email,
            'MERCHANT_KEY' => $merchantToken
        ];

        $configMock = $this->getMock(ConfigInterface::class);
        $orderMock = $this->getMock(OrderAdapterInterface::class);
        $addressMock = $this->getMock(AddressAdapterInterface::class);
        $payment = $this->getMock(PaymentDataObjectInterface::class);

        $payment->expects(static::any())
            ->method('getOrder')
            ->willReturn($orderMock);

        $orderMock->expects(static::any())
            ->method('getShippingAddress')
            ->willReturn($addressMock);

        $orderMock->expects(static::once())
            ->method('getOrderIncrementId')
            ->willReturn($invoiceId);
        $orderMock->expects(static::once())
            ->method('getGrandTotalAmount')
            ->willReturn($grandTotal);
        $orderMock->expects(static::once())
            ->method('getCurrencyCode')
            ->willReturn($currencyCode);
        $orderMock->expects(static::any())
            ->method('getStoreId')
            ->willReturn($storeId);

        $addressMock->expects(static::once())
            ->method('getEmail')
            ->willReturn($email);

        $configMock->expects(static::once())
            ->method('getValue')
            ->with('merchant_gateway_key', $storeId)
            ->willReturn($merchantToken);

        /** @var ConfigInterface $configMock */
        $request = new AuthorizationRequest($configMock);

        static::assertEquals(
            $expectation,
            $request->build(['payment' => $payment])
        );
    }
}
