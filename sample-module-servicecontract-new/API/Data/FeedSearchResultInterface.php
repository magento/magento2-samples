<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\API\Data;


interface FeedSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get feeds list.
     *
     * @api
     * @return FeedInterface[]
     */
    public function getItems();

    /**
     * Set feeds list.
     *
     * @api
     * @param FeedInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}
