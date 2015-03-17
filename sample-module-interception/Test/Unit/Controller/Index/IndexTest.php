<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Block;

class IndexTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $viewMock = $this->getMock('Magento\Framework\App\ViewInterface');

        $context = $this->getMockBuilder('Magento\Framework\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();
        
        $context->expects($this->any())->method('getView')->willReturn($viewMock);
        $context->expects($this->any())->method('getRequest')->willReturn(
            $this->getMock('Magento\Framework\App\RequestInterface')
        );
        $context->expects($this->any())->method('getResponse')->willReturn(
            $this->getMock('Magento\Framework\App\ResponseInterface')
        );

        $model = new \Magento\SampleInterception\Controller\Index\Index($context);

        // Expectations of test
        $viewMock->expects($this->once())->method('loadLayout');
        $viewMock->expects($this->once())->method('renderLayout');

        $model->execute();

    }
}
