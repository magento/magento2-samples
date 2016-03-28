<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleWebFlow\Test\Unit\Controller;

abstract class AbstractWebflowControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\Framework\TestFramework\Unit\Helper\ObjectManager */
    protected $objectManager;

    /** @var  \Magento\Framework\App\Action\Action */
    protected $controller;

    /** @var string The name of the SUT */
    protected $className;

    /** @var  \Magento\Framework\View\Result\PageFactory | \PHPUnit_Framework_MockObject_MockObject*/
    protected $resultPageFactory;

    /** @var  \Magento\Framework\View\Result\Page | \PHPUnit_Framework_MockObject_MockObject */
    protected $page;

    public function setUp()
    {
        if (!is_a($this->className, 'Magento\Framework\App\Action\Action', true)) {
            throw new \Exception("AbstractWebflowControllerTest can not be used to test $this->className.");
        }

        // Mock dependencies
        $this->page = $this->getMockBuilder('Magento\Framework\View\Result\Page')
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultPageFactory = $this->getMockBuilder('Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->getMock();

        // Set up SUT
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->controller = $this->objectManager->getObject(
            $this->className,
            ['resultPageFactory' => $this->resultPageFactory]
        );
    }

    public function testExecute()
    {
        // Define test expectations
        $this->resultPageFactory->expects($this->once())->method('create')->willReturn($this->page);
        $this->assertSame($this->page, $this->controller->execute());
    }
}
