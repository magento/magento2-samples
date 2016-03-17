<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model;

use Magento\SampleServiceContractNew\API\Data\FeedInterface;
use Magento\SampleServiceContractNew\Model\Data\Feed as DataFeed;

class SampleFeed extends DataFeed implements FeedInterface
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return __('Feed Title');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return __('Feed description');
    }
}
