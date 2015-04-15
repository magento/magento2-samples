<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Block;


use Magento\Framework\View\Element\Template;
use Magento\SampleServiceContractNew\API\FeedRepositoryInterface;

class FeedList extends Template
{
    /**
     * @var FeedRepositoryInterface
     */
    private $feedRepository;

    /**
     * @param Template\Context $context
     * @param FeedRepositoryInterface $feedRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        FeedRepositoryInterface $feedRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->feedRepository = $feedRepository;
    }

    /**
     * @return \Magento\SampleServiceContractNew\API\Data\FeedInterface[]
     */
    public function getFeeds()
    {
        return $this->feedRepository->getList();
    }

}
