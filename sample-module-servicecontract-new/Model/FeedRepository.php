<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model;


use Magento\Framework\App\Action\NotFoundException;
use \Magento\SampleServiceContractNew\API\FeedRepositoryInterface;

class FeedRepository implements FeedRepositoryInterface
{
    /**
     * @var FeedManager
     */
    private $feedManager;

    /**
     * @param FeedManager $feedManager
     */
    public function __construct(FeedManager $feedManager)
    {
        $this->feedManager = $feedManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        return $this->feedManager->getFeeds();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($feedId)
    {
        $feed = $this->feedManager->getFeed($feedId);
        if (!$feed) {
            throw new NotFoundException('Feed ' . $feedId . ' not found');
        }
        return $feed;
    }
}
