<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ExternalLinks\Api;

interface ExternalLinksProvider
{
    /**
     * @param int $productId
     * @return array
     */
    public function getExternalLinks($productId);
}