<?php
/***
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleWebFlow\Test\Unit\Controller;

abstract class AbstractWebflowControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\Framework\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject */
    protected $context;

    /** @var  \Magento\Framework\App\ResponseInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $response;

    /** @var  \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $request;

    /** @var  \Magento\Framework\App\ViewInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $view;

    /** @var  \Magento\Framework\TestFramework\Unit\Helper\ObjectManager */
    protected $objectManager;

    /** @var  \Magento\Framework\App\Action\Action */
    protected $controller;

    /** @var string The name of the specific instance  */
    protected $className;

    public function setUp()
    {
        $actionClass = 'Magento\Framework\App\Action\Action';

        // Mock dependencies
        $this->context = $this->getMockBuilder('Magento\Framework\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $this->response = $this->getMock('Magento\Framework\App\ResponseInterface');
        $this->request = $this->getMock('Magento\Framework\App\RequestInterface');
        $this->view = $this->getMock('Magento\Framework\App\ViewInterface');

        $this->context->expects($this->any())->method('getRequest')->will($this->returnValue($this->request));
        $this->context->expects($this->any())->method('getResponse')->will($this->returnValue($this->response));
        $this->context->expects($this->any())->method('getView')->will($this->returnValue($this->view));

        // Set up SUT
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controller = $this->objectManager->getObject(
            $this->className,
            ['context' => $this->context]
        );
        if (!($this->controller instanceof $actionClass)) {
            throw new \Exception("AbstractWebflowControllerTest can not be used to test $this->className.");
        }
    }

    public function testExecute()
    {
        // Define test expectations
        $this->view->expects($this->once())
            ->method('loadLayout');
        $this->view->expects($this->once())
            ->method('renderLayout');

        $this->controller->execute();
    }
}
