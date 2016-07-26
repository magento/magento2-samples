<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExternalLinks\Model\ExternalLinks;

use Magento\ExternalLinks\Model\ResourceModel\ExternalLinks\Loader;
use Magento\Framework\EntityManager\EntityManager;
use Magento\ExternalLinks\Api\Data\ExternalLinkInterface;
use Magento\ExternalLinks\Model\ExternalLink;
use Magento\ExternalLinks\Model\ExternalLinkFactory;

class ProviderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  EntityManager | \PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var  Loader | \PHPUnit_Framework_MockObject_MockObject */
    private $loader;

    /** @var  ExternalLinkFactory | \PHPUnit_Framework_MockObject_MockObject */
    private $externalLinkFactory;

    /** @var  Provider */
    private $provider;

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loader = $this->getMockBuilder(Loader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->externalLinkFactory = $this->getMockBuilder(ExternalLinkFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->provider = new Provider(
            $this->entityManager,
            $this->loader,
            $this->externalLinkFactory
        );
    }

    public function testGetExternalLinks()
    {
        $ids = [1, 2];
        $productId = 3;
        $this->loader->expects($this->once())
            ->method("getIdsByProductId")
            ->with($productId)
            ->willReturn($ids);

        $this->externalLinkFactory->expects($this->exactly(2))
            ->method("create"); 
        $this->entityManager->expects($this->exactly(2))
            ->method("load");

        $this->provider->getLinks($productId);
    }
}


