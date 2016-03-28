<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\SampleServiceContractNew\API\Data\FeedInterface;
use Magento\SampleServiceContractNew\API\Data\FeedSearchResultInterface;
use Magento\SampleServiceContractNew\API\Data\FeedSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\SampleServiceContractNew\API\FeedRepositoryInterface;

class FeedRepository implements FeedRepositoryInterface
{
    /**
     * @var FeedManager
     */
    private $feedManager;
    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @param FeedManager $feedManager
     * @param SearchResultFactory $searchResultFactory
     */
    public function __construct(
        FeedManager $feedManager,
        SearchResultFactory $searchResultFactory
    ) {
        $this->feedManager = $feedManager;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $feeds = $this->feedManager->getFeeds();
        /** @var FeedSearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setItems($this->getFilteredFeeds($feeds, $searchCriteria));
        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($feedId)
    {
        $feed = $this->feedManager->getFeed($feedId);
        if (!$feed) {
            throw new NotFoundException(__('Feed ' . $feedId . ' not found'));
        }
        return $feed;
    }

    /**
     * Implementation of simple feeds filtration by SearchCriteria
     *
     * @param FeedInterface[] $feeds
     * @param SearchCriteriaInterface $searchCriteria
     * @return array
     */
    private function getFilteredFeeds(array $feeds, SearchCriteriaInterface $searchCriteria)
    {
        $filteredFeeds = [];
        $filterGroups = $searchCriteria->getFilterGroups();
        foreach ($feeds as $feed) {
            if ($this->isFeedMatchesFilterGroups($filterGroups, $feed)) {
                $filteredFeeds[] = $feed;
            }
        }
        return $filteredFeeds;
    }


    /**
     * @param FilterGroup[] $filterGroups
     * @param FeedInterface $feed
     * @return bool
     */
    private function isFeedMatchesFilterGroups(array $filterGroups, FeedInterface$feed)
    {
        foreach ($filterGroups as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if (!$this->isFeedMatchesFilter($feed, $filter)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param FeedInterface $feed
     * @param Filter $filter
     * @return bool
     */
    private function isFeedMatchesFilter(FeedInterface $feed, Filter $filter)
    {
        switch (strtolower($filter->getField())) {
            case 'id':
                $isMatches = $filter->getValue() === $feed->getId();
                break;
            case 'title':
                $isMatches = strpos($feed->getTitle(), $filter->getValue()) !== false;
                break;
            case 'description':
                $isMatches = strpos($feed->getDescription(), $filter->getValue()) !== false;
                break;
            default:
                $isMatches = false;
                break;
        }
        return $isMatches;
    }
}
