<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ExternalLinks\Model\ResourceModel\ExternalLinks;

use Magento\Framework\App\ResourceConnection;
use Magento\ExternalLinks\Api\Data\ExternalLinkInterface;

/**
 * Class Loader
 * @package Magento\ExternalLinks\Model\ExternalLinks
 */
class Loader
{
    /** @var  \Magento\Framework\EntityManager\MetadataPool */
    private $metadataPool;

    /** @var  ResourceConnection\ */
    private $resourceConnection;

    /**
     * Loader constructor.
     * @param \Magento\Framework\EntityManager\MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct
    (
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        ResourceConnection $resourceConnection
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param $productId
     * @return array
     * @throws \Exception
     */
    public function getIdsByProductId($productId)
    {
        $metadata = $this->metadataPool->getMetadata(ExternalLinkInterface::class);
        $connection = $this->resourceConnection->getConnection();

        $select = $connection
            ->select()
            ->from($metadata->getEntityTable(), ExternalLinkInterface::LINK_ID)
            ->where(ExternalLinkInterface::PRODUCT_ID . ' = ?', $productId);
        $ids = $connection->fetchCol($select);

        return $ids ?: [];
    }
}