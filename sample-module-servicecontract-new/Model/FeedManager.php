<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model;


use Magento\Framework\Api\DataObjectHelper;
use Magento\SampleServiceContractNew\API\Data\FeedInterface;

class FeedManager
{
    /**
     * @var FeedInterface[]
     */
    private $feeds;
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param UrlBuilder $urlBuilder
     * @param FeedInterface[] $feeds
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        UrlBuilder $urlBuilder,
        array $feeds = []
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->urlBuilder = $urlBuilder;
        $this->feeds = $feeds;
    }

    /**
     * @param string $feedId
     * @return FeedInterface[]|null
     */
    public function getFeed($feedId)
    {
        $feed = null;
        if (array_key_exists($feedId, $this->feeds)) {
            $feed = $this->feeds[$feedId];
            $this->populateFeed($feedId, $feed);
        }
        return $feed;
    }

    /**
     * @return FeedInterface[]
     */
    public function getFeeds()
    {
        $feeds = [];
        foreach ($this->feeds as $id => $feed) {
            $feeds[] = $this->populateFeed($id, $feed);
        }

        return $feeds;
    }

    /**
     * @param string $id
     * @param FeedInterface $feed
     * @return FeedInterface
     */
    private function populateFeed($id, FeedInterface $feed)
    {
        $this->dataObjectHelper->populateWithArray(
            $feed,
            [
                FeedInterface::KEY_ID => $id,
                FeedInterface::KEY_LINK => $this->urlBuilder->getUrl(['type' => $id])
            ],
            'Magento\SampleServiceContractNew\API\Data\FeedInterface'
        );

        return $feed;
    }
}
