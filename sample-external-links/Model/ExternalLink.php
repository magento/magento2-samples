<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExternalLinks\Model;

use Magento\Framework\DataObject;
use Magento\ExternalLinks\Api\Data\ExternalLinkInterface;

/**
 * Class ExternalLink
 * @package Magento\ExternalLinks\Model
 */
final class ExternalLink implements ExternalLinkInterface
{
    /** @var  array */
    private $link;

    /** @var  int */
    private $linkId;

    /** @var  int */
    private $productId;

    /** @var  string */
    private $linkType;

    /** @var  array  */
    private $extenstionAttributes;

    /**
     * @inheritdoc
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @inheritdoc
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLinkType()
    {
        return $this->linkType;
    }

    /**
     * @inheritdoc
     */
    public function setLinkType($type)
    {
        $this->linkType = $type;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLinkId()
    {
        return $this->linkId;
    }

    /**
     * @inheritdoc
     */
    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
        return $this->linkId;
    }

    /**
     * @inheritdoc
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @inheritdoc
     */
    public function setProductId($id)
    {
        $this->productId = $id;
        return $this; 
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(
        \Magento\ExternalLinks\Api\Data\ExternalLinkExtensionInterface $extensionAttributes
    )
    {
       $this->extenstionAttributes = $extensionAttributes;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->extenstionAttributes;
    }
}