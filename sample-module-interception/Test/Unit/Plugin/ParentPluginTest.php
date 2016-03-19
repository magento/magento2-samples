<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Plugin;

class ParentPluginTest extends \PHPUnit_Framework_TestCase
{
    public function testAfterBaseMethodReverse()
    {
        $model = new \Magento\SampleInterception\Plugin\ParentPlugin();
        $subjectMock = $this->getMock('Magento\SampleInterception\Model\Intercepted\ChildInherit');
        $inStr = 'reverse me';
        $output = "(parent plugin) $inStr (/parent plugin)";
        $this->assertSame($output, $model->afterBaseMethodReverse($subjectMock, $inStr));
    }
}
