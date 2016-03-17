<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Test\Unit\Controller\Feed;


use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\SampleServiceContractNew\API\FeedRepositoryInterface;
use Magento\SampleServiceContractNew\Controller\Feed\View;
use Magento\SampleServiceContractNew\Model\FeedTransformer;

class ViewTest extends \PHPUnit_Framework_TestCase
{
    /** @var  FeedTransformer|\PHPUnit_Framework_MockObject_MockObject */
    private $feedTransformer;
    /** @var  FeedRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $feedRepository;
    /** @var \PHPUnit_Framework_MockObject_MockObject|RequestInterface */
    private $request;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\ResponseInterface */
    private $response;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Context */
    private $context;
    /** @var  View */
    private $controller;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->context = $this->getMockBuilder('Magento\Framework\App\Action\Context')
            ->disableOriginalConstructor()
            ->setMethods(['getRequest', 'getResponse'])
            ->getMock();
        $this->response = $this->getMockBuilder('Magento\Framework\App\Response\Http')
            ->disableOriginalConstructor()
            ->getMock();
        $this->context->expects($this->once())
            ->method('getResponse')
            ->willReturn($this->response);
        $this->request = $this->getMockBuilder('Magento\Framework\App\RequestInterface')
            ->getMockForAbstractClass();
        $this->context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->feedRepository = $this->getMockBuilder('Magento\SampleServiceContractNew\API\FeedRepositoryInterface')
            ->getMockForAbstractClass();
        $this->feedTransformer = $this->getMockBuilder('Magento\SampleServiceContractNew\Model\FeedTransformer')
            ->disableOriginalConstructor()
            ->getMock();
        $this->controller = $objectManager->getObject(
            'Magento\SampleServiceContractNew\Controller\Feed\View',
            [
                'feedRepository' => $this->feedRepository,
                'feedTransformer' => $this->feedTransformer,
                'context' => $this->context,
            ]
        );
    }

    public function testExecute()
    {
        $type = 'sampleType';
        $feed = $this->getMockBuilder('Magento\SampleServiceContractNew\API\Data\FeedInterface')
            ->getMockForAbstractClass();
        $xml = 'xmlDataString';
        $this->request->expects($this->once())
            ->method('getParam')
            ->with('type')
            ->willReturn($type);
        $this->feedRepository->expects($this->once())
            ->method('getById')
            ->with($type)
            ->willReturn($feed);
        $this->response->expects($this->once())
            ->method('setHeader')
            ->with('Content-type', 'text/xml; charset=UTF-8')
            ->willReturnSelf();
        $this->feedTransformer->expects($this->once())
            ->method('toXml')
            ->with($feed)
            ->willReturn($xml);
        $this->response->expects($this->once())
            ->method('setBody')
            ->with($xml)
            ->willReturnSelf();
        $this->controller->execute();
    }
}
