<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExternalLinks\Model\ResourceModel\ExternalLinks;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\ExternalLinks\Api\Data\ExternalLinkInterface;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \Magento\Framework\EntityManager\MetadataPool | \PHPUnit_Framework_MockObject_MockObject */
    private $metadataPool;

    /** @var  ResourceConnection | \PHPUnit_Framework_MockObject_MockObject */
    private $resourceConnection;

    /** @var  Loader */
    private $loader;

    public function setUp()
    {
        $this->metadataPool = $this->getMockBuilder(\Magento\Framework\EntityManager\MetadataPool::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->resourceConnection = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loader = new Loader($this->metadataPool, $this->resourceConnection);
    }

    public function testGetIdsByProductId()
    {
        $ids = [1, 2];
        $productId = 1;
        $entityTable = "product_external_links";
        $metadata = $this->getMock(EntityMetadataInterface::class);
        $adapter = $this->getMock(AdapterInterface::class);
        $metadata->expects($this->once())
            ->method("getEntityTable")
            ->willReturn($entityTable);
        $this->metadataPool->expects($this->once())
            ->method("getMetaData")
            ->willReturn($metadata);

        $select = $this->getMockBuilder(\Zend_Db_Select::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->resourceConnection->expects($this->once())
            ->method("getConnection")
            ->willReturn($adapter);
        $adapter->expects($this->once())
            ->method("select")
            ->willReturn($select);
        $select->expects($this->once())
            ->method("from")
            ->with($entityTable, ExternalLinkInterface::LINK_ID)
            ->willReturn($select);
        $select->expects($this->once())
            ->method("where")
            ->with(ExternalLinkInterface::PRODUCT_ID . ' = ?', $productId)
            ->willReturn($select);
        $adapter->expects($this->once())
            ->method('fetchCol')
            ->willReturn($ids);

        $this->assertEquals(
            $ids,
            $this->loader->getIdsByProductId($productId)
        );
    }
}


