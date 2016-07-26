<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExternalLinks\Model\Plugin\Product;

use Magento\Catalog\Api\Data\ProductExtensionFactory;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ExternalLinks\Api\ExternalLinksProviderInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\ExternalLinks\Api\Data\ExternalLinkInterface;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Repository */
    private $repository;

    /** @var  ProductExtensionFactory | \PHPUnit_Framework_MockObject_MockObject */
    private $productExtensionFactory;

    /** @var  EntityManager | \PHPUnit_Framework_MockObject_MockObject */
    private $entityManager;

    /** @var  ProductRepositoryInterface | \PHPUnit_Framework_MockObject_MockObject */
    private $subject;

    /** @var  ProductExtensionInterface | \PHPUnit_Framework_MockObject_MockObject */
    private $productExtensionAttributes;

    /** @var  ProductInterface | \PHPUnit_Framework_MockObject_MockObject */
    private $product;

    /** @var  ExternalLinksProviderInterface | \PHPUnit_Framework_MockObject_MockObject */
    private $externalLinkProvider;

    public function setUp()
    {
        $this->productExtensionFactory = $this->getMockBuilder(ProductExtensionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->product = $this->getMock(ProductInterface::class);
        $this->productExtensionAttributes = $this->getMock(ProductExtensionInterface::class);

        $this->externalLinkProvider = $this->getMockBuilder(ExternalLinksProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = $this->getMock(ProductRepositoryInterface::class);

        $this->entityManager = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repository = new Repository(
            $this->productExtensionFactory,
            $this->entityManager,
            $this->externalLinkProvider
        );
    }

    public function testAfterGetWithEmptyExtensionAttributes()
    {
        $productId = 1;
        $externalLinks = ['Some dummy content'];
        $this->product->expects($this->atLeastOnce())
            ->method("getId")
            ->willReturn($productId);
        $this->productExtensionFactory->expects($this->once())
            ->method("create")
            ->willReturn($this->productExtensionAttributes);
        $this->externalLinkProvider->expects($this->once())
            ->method("getLinks")
            ->with($productId)
            ->willReturn($externalLinks);
        $this->productExtensionAttributes->expects($this->once())
            ->method("setExternalLinks")
            ->with($externalLinks);
        $this->product->expects($this->once())
            ->method("setExtensionAttributes")
            ->with($this->productExtensionAttributes);

        $this->repository->afterGet($this->subject, $this->product);
    }

    public function testAfterGetWithExtensionAttributes()
    {
        $productId = 1;
        $externalLinks = ['Some dummy content'];
        $this->product->expects($this->atLeastOnce())
            ->method("getId")
            ->willReturn($productId);
        $this->product->expects($this->once())
            ->method("getExtensionAttributes")
            ->willReturn($this->productExtensionAttributes);
        $this->externalLinkProvider->expects($this->once())
            ->method("getLinks")
            ->with($productId)
            ->willReturn($externalLinks);
        $this->productExtensionAttributes->expects($this->once())
            ->method("setExternalLinks")
            ->with($externalLinks);
        $this->product->expects($this->once())
            ->method("setExtensionAttributes")
            ->with($this->productExtensionAttributes);

        $this->repository->afterGet($this->subject, $this->product);
    }

    public function testAfterGetList()
    {
        $productId = 1;
        $fakeProductId = 2;
        $fakeProduct2 = $this->getMock(ProductInterface::class);
        $externalLinks = ['Some dummy content', 'Some dummy content 2'];

        $searchResult = $this->getMockBuilder(\Magento\Framework\Api\SearchResults::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->product->expects($this->atLeastOnce())
            ->method("getId")
            ->willReturn($productId);
        $fakeProduct2->expects($this->atLeastOnce())
            ->method("getId")
            ->willReturn($fakeProductId);
        $searchResult->expects($this->once())
            ->method("getItems")
            ->willReturn([$this->product, $fakeProduct2]);
        $this->productExtensionFactory->expects($this->exactly(2))
            ->method("create")
            ->willReturn($this->productExtensionAttributes);
        $this->externalLinkProvider->expects($this->exactly(2))
            ->method("getLinks")
            ->willReturn($externalLinks);
        $this->productExtensionAttributes->expects($this->exactly(2))
            ->method("setExternalLinks")
            ->with($externalLinks);
        $this->product->expects($this->once())
            ->method("setExtensionAttributes")
            ->with($this->productExtensionAttributes);
        $fakeProduct2->expects($this->once())
            ->method("setExtensionAttributes")
            ->with($this->productExtensionAttributes);

        $this->repository->afterGetList($this->subject, $searchResult);
    }

    public function testAfterSave()
    {
        $previousProduct = $this->getMock(ProductInterface::class);
        $productSku = 'sku';
        $productId = 3;
        $link = $this->getMock(ExternalLinkInterface::class);
        $this->product->expects($this->atLeastOnce())
            ->method("getId")
            ->willReturn($productId);

        $link->expects($this->once())
            ->method("setProductId")
            ->with($productId);

        $externalLinks = [$link];

        $previousProduct->expects($this->once())
            ->method("getExtensionAttributes")
            ->willReturn($this->productExtensionAttributes);

        $this->product->expects($this->once())
            ->method("getExtensionAttributes")
            ->willReturn($this->productExtensionAttributes);

        $reflection = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflection->getProperty('currentProduct');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->repository, $previousProduct);

        $this->productExtensionAttributes->expects($this->atLeastOnce())
            ->method("getExternalLinks")
            ->willReturn($externalLinks);

        $this->entityManager->expects($this->once())
            ->method("save")
            ->with($link);

        $this->repository->afterSave($this->subject, $this->product);
    }
}


