<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\API;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\SearchResultInterface;
use Magento\SampleServiceContractNew\API\Data\FeedInterface;

/**
 * Interface FeedRepositoryInterface
 */
interface FeedRepositoryInterface
{
    /**
     * Get array of all possible feeds
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Get feed by it's identifier
     *
     * @param string $feedId
     * @return FeedInterface
     */
    public function getById($feedId);
}
