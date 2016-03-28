<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Plugin;

class PluginBeforeTest extends \PHPUnit_Framework_TestCase
{
    public function testBeforeBaseMethod()
    {
        $model = new \Magento\SampleInterception\Plugin\PluginBefore();
        $subjectMock = $this->getMock('Magento\SampleInterception\Model\Intercepted\ChildBefore');
        $inStr = 'gruchenka';
        $output = ["(before) $inStr (/before)"];
        $this->assertSame($output, $model->beforeBaseMethodUppercase($subjectMock, $inStr));
    }
}
