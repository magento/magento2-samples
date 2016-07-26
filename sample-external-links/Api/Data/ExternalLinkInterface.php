<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExternalLinks\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ExternalLinkInterface extends ExtensibleDataInterface
{
    const TYPE = "type";

    const LINK = "link"; 

    const PRODUCT_ID = "product_id";

    const LINK_ID = "link_id";

    /**
     * Retrieve Link Type
     *
     * @return string
     */
    public function getLinkType();

    /**
     * Set Link Type
     *
     * @param string $type
     * @return self
     */
    public function setLinkType($type);

    /**
     * Retrieve Provider link
     *
     * @return string
     */
    public function getLink();

    /**
     * Set Provider link
     *
     * @param string $link
     * @return self
     */
    public function setLink($link);

    /**
     * Set Product Id for further updates
     *
     * @param int $id
     * @return self
     */
    public function setProductId($id);

    /**
     * Retrieve product id
     *
     * @return int
     */
    public function getProductId();

    /**
     * @return int
     */
    public function getLinkId();

    /**
     * @param int $linkId
     * @return self
     */
    public function setLinkId($linkId);

    /**
     * @return \Magento\ExternalLinks\Api\Data\ExternalLinkExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \Magento\ExternalLinks\Api\Data\ExternalLinkExtensionInterface $extensionAttributes
     * @return self
     */
    public function setExtensionAttributes
    (
        \Magento\ExternalLinks\Api\Data\ExternalLinkExtensionInterface $extensionAttributes
    ); 
}