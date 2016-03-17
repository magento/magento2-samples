<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Block;


use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;
use Magento\SampleServiceContractNew\API\FeedRepositoryInterface;

class FeedList extends Template
{
    /**
     * @var FeedRepositoryInterface
     */
    private $feedRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param Template\Context $context
     * @param FeedRepositoryInterface $feedRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        FeedRepositoryInterface $feedRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->feedRepository = $feedRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return \Magento\SampleServiceContractNew\API\Data\FeedInterface[]
     */
    public function getFeeds()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->feedRepository->getList($searchCriteria);
        return $searchResult->getItems();
    }

}
