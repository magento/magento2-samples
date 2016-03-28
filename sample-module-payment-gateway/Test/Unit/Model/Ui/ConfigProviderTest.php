<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SamplePaymentGateway\Test\Unit\Model\Ui;

use Magento\SamplePaymentGateway\Gateway\Http\Client\ClientMock;
use Magento\SamplePaymentGateway\Model\Ui\ConfigProvider;

class ConfigProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfig()
    {
        $configProvider = new ConfigProvider();

        static::assertEquals(
            [
                'payment' => [
                    ConfigProvider::CODE => [
                        'transactionResults' => [
                            ClientMock::SUCCESS => __('Success'),
                            ClientMock::FAILURE => __('Fraud')
                        ]
                    ]
                ]
            ],
            $configProvider->getConfig()
        );
    }
}
