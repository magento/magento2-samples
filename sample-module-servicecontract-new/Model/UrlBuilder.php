<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model;


use Magento\Framework\UrlInterface;

class UrlBuilder
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param array $queryParams
     * @return string
     */
    public function getUrl(array $queryParams = [])
    {
        return $this->urlBuilder->getUrl('sampleservicecontractnew/feed/view', $queryParams);
    }
}
