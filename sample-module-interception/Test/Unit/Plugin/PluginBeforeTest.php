<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

class PluginBeforeTest extends \PHPUnit_Framework_TestCase
{
    public function testBeforeBaseMethod()
    {
        $model = new \Magento\SampleInterception\Plugin\PluginBefore();
        $subjectMock = $this->getMock('Magento\SampleInterception\Helper\Intercepted\ChildBefore');
        $inStr = 'gruchenka';
        $output = ["(before) $inStr (/before)"];
        $this->assertSame($output, $model->beforeBaseMethod($subjectMock, $inStr));
    }
}
