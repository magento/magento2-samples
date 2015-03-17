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
        $nextUrl = 'url/to/next/page';
        $urlBuilder = $this->getMock('Magento\Framework\UrlInterface');
        $urlBuilder->expects($this->once())->method('getUrl')->with('webflow/nextpage')->willReturn($nextUrl);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $model = $objectManager->getObject('Magento\SampleWebFlow\Block\FirstPage', ['urlBuilder' => $urlBuilder]);
        $this->assertSame($nextUrl, $model->getNextPageUrl());
    }
}
