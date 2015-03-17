<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

class PluginAfterTest extends \PHPUnit_Framework_TestCase
{
    public function testAfterBaseMethod()
    {
        $model = new \Magento\SampleInterception\Plugin\PluginAfter();
        $subjectMock = $this->getMock('Magento\SampleInterception\Helper\Intercepted\ChildAfter');
        $inStr = 'raskolnikov';
        $outStr = "(after) $inStr (/after)";
        $this->assertSame($outStr, $model->afterBaseMethod($subjectMock, $inStr));
    }
}
