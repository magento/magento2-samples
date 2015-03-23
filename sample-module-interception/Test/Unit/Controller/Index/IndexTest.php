<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Test\Unit\Controller\Index;

class IndexTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        // Create dependency mocks
        $page = $this->getMockBuilder('Magento\Framework\View\Result\Page')
            ->disableOriginalConstructor()
            ->getMock();
        $resultFactory = $this->getMockBuilder('Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->getMock();;

        $context = $this->getMockBuilder('Magento\Framework\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $context->expects($this->any())->method('getView')->willReturn($resultFactory);
        $context->expects($this->any())->method('getRequest')->willReturn(
            $this->getMock('Magento\Framework\App\RequestInterface')
        );
        $context->expects($this->any())->method('getResponse')->willReturn(
            $this->getMock('Magento\Framework\App\ResponseInterface')
        );

        // Set up SUT
        $model = new \Magento\SampleInterception\Controller\Index\Index($context, $resultFactory);

        // Expectations of test
        $resultFactory->expects($this->once())->method('create')->willReturn($page);

        $this->assertSame($page, $model->execute());
    }
}
