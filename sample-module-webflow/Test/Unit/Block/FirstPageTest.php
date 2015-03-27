<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleWebFlow\Test\Unit\Block;

class FirstPageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNextPageUrl()
    {
        // Set up mock data
        $nextUrl = 'magento.com/url/to/next/page';
        $inputData = ['url' => $nextUrl];
        $escaperMock = $this->getMock('Magento\Framework\Escaper');
        $escaperMock->expects($this->once())
            ->method('escapeHtml')
            ->with($nextUrl)
            ->willReturn($nextUrl);

        // Set up SUT
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $model = $objectManager->getObject(
            'Magento\SampleWebFlow\Block\FirstPage',
            [
                'data' => $inputData,
                'escaper' => $escaperMock
            ]
        );

        $this->assertSame($nextUrl, $model->getNextPageUrl());
    }
}
