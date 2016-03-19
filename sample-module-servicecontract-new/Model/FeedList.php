<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model;


use Magento\SampleServiceContractNew\API\Data\FeedInterface;
use \Magento\SampleServiceContractNew\API\FeedListInterface;

class FeedList implements FeedListInterface
{
    /**
     * @var FeedManager
     */
    private $rssManager;
    /**
     * @param FeedManager $rssManager
     */
    public function __construct(
        FeedManager $rssManager
    ) {
        $this->rssManager = $rssManager;
    }


    /**
     * @return FeedInterface[]
     */
    public function getFeeds()
    {
        /** @var FeedInterface[] $feeds */
        $feeds = $this->rssManager->getFeeds();
        return $feeds;
    }

}
