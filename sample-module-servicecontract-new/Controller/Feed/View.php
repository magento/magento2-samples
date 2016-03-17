<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Controller\Feed;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\SampleServiceContractNew\API\Data\FeedInterface;
use Magento\SampleServiceContractNew\API\FeedRepositoryInterface;
use Magento\SampleServiceContractNew\Model\FeedTransformer;

class View extends Action
{
    /**
     * @var FeedTransformer
     */
    private $feedTransformer;
    /**
     * @var FeedRepositoryInterface
     */
    private $feedRepository;

    /**
     * @param Context $context
     * @param FeedRepositoryInterface $feedRepository
     * @param FeedTransformer $feedTransformer
     */
    public function __construct(
        Context $context,
        FeedRepositoryInterface $feedRepository,
        FeedTransformer $feedTransformer
    ) {
        parent::__construct($context);
        $this->feedRepository = $feedRepository;
        $this->feedTransformer = $feedTransformer;
    }

    /**
     * @return void
     */
    public function execute()
    {
        $feedId = $this->getRequest()->getParam('type');

        /** @var FeedInterface $feed */
        $feed = $this->feedRepository->getById($feedId);

        $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
        $this->getResponse()->setBody($this->feedTransformer->toXml($feed));
    }
}
