<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\API;

use Magento\SampleServiceContractNew\API\Data\FeedInterface;

/**
 * Interface FeedRepositoryInterface
 */
interface FeedRepositoryInterface
{
    /**
     * Get array of all possible feeds
     *
     * @return FeedInterface[]
     */
    public function getList();

    /**
     * Get feed by it's identifier
     *
     * @param string $feedId
     * @return FeedInterface
     */
    public function getById($feedId);
}
