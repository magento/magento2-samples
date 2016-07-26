<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ExternalLinks\Api;

interface ExternalLinksProviderInterface
{
    /**
     * @param int $productId
     * @return array
     */
    public function getLinks($productId);
}