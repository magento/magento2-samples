<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SamplePaymentProvider\Model\Config\Source\Order\Status;

class PendingpaymentTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Magento\SamplePaymentProvider\Model\Config\Source\Order\Status\Pendingpayment */
    protected $object;

    /** @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager */
    protected $objectManager;

    /** @var \Magento\Sales\Model\Order\Config|\PHPUnit_Framework_MockObject_MockObject */
    protected $config;

    protected function setUp()
    {
        $this->config = $this->getMock('Magento\Sales\Model\Order\Config', [], [], '', false);

        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->object = $this->objectManager->getObject(
            '\Magento\SamplePaymentProvider\Model\Config\Source\Order\Status\Pendingpayment',
            ['orderConfig' => $this->config]
        );
    }

    public function testToOptionArray()
    {
        $this->config
            ->expects($this->once())->method('getStateStatuses')
            ->with(['pending_payment'])
            ->will($this->returnValue(['pending_payment' => 'Pending Payment']));

        $this->assertEquals(
            [
                ['value' => '', 'label' => '-- Please Select --'],
                ['value' => 'pending_payment', 'label' => 'Pending Payment']
            ],
            $this->object->toOptionArray()
        );
    }
}
