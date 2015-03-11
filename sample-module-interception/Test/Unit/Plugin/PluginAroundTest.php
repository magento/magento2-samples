<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

class PluginAroundTest extends \PHPUnit_Framework_TestCase
{
    public function testAroundBaseMethod()
    {
        $model = new \M2Demo\PluginDemo\Plugin\PluginAround();
        $subjectMock = $this->getMock('M2Demo\PluginDemo\Helper\Intercepted\ChildAround');
        $inStr = 'zosima';
        $outStr =
            "(around: after helper) (around: before helper) $inStr (/around: before helper) (/around: after helper)";

        $proceed = function($in)
        {
            return $in;
        };

        $this->assertSame($outStr, $model->aroundBaseMethod($subjectMock, $proceed, $inStr));

    }
}
