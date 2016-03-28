<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model;


use Magento\SampleServiceContractNew\API\Data\FeedInterface;

class FeedTransformer
{
    /**
     * @var \Zend_Feed
     */
    private $zendFeed;

    /**
     * @param \Zend_Feed $zendFeed
     */
    public function __construct(\Zend_Feed $zendFeed)
    {
        $this->zendFeed = $zendFeed;
    }

    /**
     * Get xml from feed data
     *
     * @codeCoverageIgnore due to static method call (\Zend_Feed::importArray)
     *
     * @param FeedInterface $feed
     * @return string
     */
    public function toXml(FeedInterface $feed)
    {
        $data = [
            'title' => $feed->getTitle(),
            'link' => $feed->getLink(),
            'charset' => 'UTF-8',
            'description' => $feed->getDescription(),
        ];
        $rssFeedFromArray = $this->zendFeed->importArray($data, 'rss');
        $xml = $rssFeedFromArray->saveXML();
        return $xml;
    }
}
