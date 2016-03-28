<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractClient\Test\Unit\Controller\Index;


class IndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\Action\Context
     */
    private $context;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\ResponseInterface
     */
    private $response;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Magento\SampleServiceContractClient\Controller\Index\Index
     */
    private $controller;

    protected function setUp()
    {
        $this->context = $this->getMockBuilder('Magento\Framework\App\Action\Context')
            ->disableOriginalConstructor()
            ->getMock();
        $this->request = $this->getMockBuilder('Magento\Framework\App\RequestInterface')
            ->getMockForAbstractClass();
        $this->context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->response = $this->getMockBuilder('Magento\Framework\App\ResponseInterface')
            ->getMockForAbstractClass();
        $this->context->expects($this->once())
            ->method('getResponse')
            ->willReturn($this->response);
        $this->resultPageFactory = $this->getMockBuilder('Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controller = $objectManager->getObject(
            '\Magento\SampleServiceContractClient\Controller\Index\Index',
            [
                'context' => $this->context,
                'resultPageFactory' => $this->resultPageFactory,
            ]
        );
    }

    public function testExecute()
    {
        $page = 'SamplePageObjectHere';
        $this->resultPageFactory->expects($this->once())
            ->method('create')
            ->willReturn($page);
        $result = $this->controller->execute();
        $this->assertEquals($page, $result);
    }
}
