<?php
namespace Magento\SampleNewPage\Test\Unit\Controller\Index;

class IndexTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        // Mock dependencies
        $context = $this->getMockBuilder('Magento\Framework\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $response = $this->getMock('Magento\Framework\App\ResponseInterface');
        $request = $this->getMock('Magento\Framework\App\RequestInterface');
        $view = $this->getMock('Magento\Framework\App\ViewInterface');

        $context->expects($this->any())->method('getRequest')->will($this->returnValue($request));
        $context->expects($this->any())->method('getResponse')->will($this->returnValue($response));
        $context->expects($this->any())->method('getView')->will($this->returnValue($view));

        // Define test expectations
        $view->expects($this->once())
            ->method('loadLayout');
        $view->expects($this->once())
            ->method('renderLayout');

        // Set up SUT
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $controller = $objectManager->getObject(
            'Magento\SampleNewPage\Controller\Index\Index',
            ['context' => $context]
        );
        $controller->execute();
    }
}
