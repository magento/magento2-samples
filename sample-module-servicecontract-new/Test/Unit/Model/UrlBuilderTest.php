<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Model;


class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\SampleServiceContractNew\Model\UrlBuilder */
    private $target;

    /** @var  \Magento\Framework\UrlInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $urlBuilder;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->urlBuilder = $this->getMockBuilder('Magento\Framework\UrlInterface')
            ->getMockForAbstractClass();
        $this->target = $objectManager->getObject('Magento\SampleServiceContractNew\Model\UrlBuilder',
            ['urlBuilder' => $this->urlBuilder]
        );
    }

    public function testGetUrl()
    {
        $queryParams = ['queryParamsArray'];
        $url = 'sampleUrl';
        $this->urlBuilder->expects($this->once())
            ->method('getUrl')
            ->with('sampleservicecontractnew/feed/view', $queryParams)
            ->willReturn($url);
        $this->assertEquals($url, $this->target->getUrl($queryParams));
    }
}
