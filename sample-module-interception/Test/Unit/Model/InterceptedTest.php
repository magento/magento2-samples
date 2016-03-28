<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Model;

class InterceptedTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\SampleInterception\Model\Intercepted */
    protected $model;

    public function setUp()
    {
        $this->model = new \Magento\SampleInterception\Model\Intercepted();
    }

    public function testBaseMethodUppercase()
    {
        $inStr = 'capitalize me';
        $outStr = 'CAPITALIZE ME';
        $this->assertSame($outStr, $this->model->baseMethodUppercase($inStr));
    }

    public function testBaseMethodReverse()
    {
        $inStr = 'abcd';
        $outStr = 'dcba';
        $this->assertSame($outStr, $this->model->baseMethodReverse($inStr));
    }
}
