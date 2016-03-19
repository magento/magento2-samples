<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleWebFlow\Test\Unit\Block;

class FirstPageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNextPageUrl()
    {
        $nextUrl = 'magento.com/url/to/next/page';
        $inputData = ['url' => $nextUrl];

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $model = $objectManager->getObject('Magento\SampleWebFlow\Block\FirstPage', ['data' => $inputData]);
        $this->assertSame($nextUrl, $model->getNextPageUrl());
    }
}
