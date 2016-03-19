<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Plugin;

class PluginAfterTest extends \PHPUnit_Framework_TestCase
{
    public function testAfterBaseMethod()
    {
        $model = new \Magento\SampleInterception\Plugin\PluginAfter();
        $subjectMock = $this->getMock('Magento\SampleInterception\Model\Intercepted\ChildAfter');
        $inStr = 'raskolnikov';
        $outStr = "(after) $inStr (/after)";
        $this->assertSame($outStr, $model->afterBaseMethodUppercase($subjectMock, $inStr));
    }
}
