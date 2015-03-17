<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

class InterceptedTest extends \PHPUnit_Framework_TestCase
{
    public function testBaseMethod()
    {
        $model = new \Magento\SampleInterception\Helper\Intercepted();
        $inStr = 'capitalize me';
        $outStr = 'CAPITALIZE ME';
        $this->assertSame($outStr, $model->baseMethod($inStr));
    }
}
