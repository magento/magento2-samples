<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExternalLinks\Model\ExternalLinks;

use Magento\ExternalLinks\Api\ExternalLinksProviderInterface;
use Magento\ExternalLinks\Model\ResourceModel\ExternalLinks\Loader;
use Magento\Framework\EntityManager\EntityManager;
use Magento\ExternalLinks\Model\ExternalLinkFactory;

/**
 * Class Provider
 * @package Magento\ExternalLinks\Model\ExternalLinks
 */
class Provider implements ExternalLinksProviderInterface
{
    /** @var  EntityManager */
    private $entityManager;

    /** @var  Loader */
    private $loader;

    /** @var  ExternalLinkFactory */
    private $externalLinkFactory;

    /**
     * Provider constructor.
     * @param EntityManager $entityManager
     * @param Loader $loader
     */
    public function __construct
    (
        EntityManager $entityManager,
        Loader $loader,
        ExternalLinkFactory $externalLinkFactory
    ) {
        $this->entityManager = $entityManager;
        $this->loader = $loader;
        $this->externalLinkFactory = $externalLinkFactory;
    }

    /**
     * @inheritdoc
     */
    public function getLinks($productId)
    {
        $externalLinks = [];
        $ids = $this->loader->getIdsByProductId($productId);

        foreach($ids as $id) {
            $externalLink = $this->externalLinkFactory->create();
            $externalLinks[] = $this->entityManager->load($externalLink, $id);
        }

        return $externalLinks; 
    }
}