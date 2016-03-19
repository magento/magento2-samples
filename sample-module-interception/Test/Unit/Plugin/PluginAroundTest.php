<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Plugin;

class PluginAroundTest extends \PHPUnit_Framework_TestCase
{
    public function testAroundBaseMethod()
    {
        $model = new \Magento\SampleInterception\Plugin\PluginAround();
        $subjectMock = $this->getMock('Magento\SampleInterception\Model\Intercepted\ChildAround');
        $inStr = 'zosima';
        $outStr =
            "(around: after base method) (around: before base method) "
            . $inStr
            . " (/around: before base method) (/around: after base method)";

        $proceed = function($in)
        {
            return $in;
        };

        $this->assertSame($outStr, $model->aroundBaseMethodUppercase($subjectMock, $proceed, $inStr));

    }
}
