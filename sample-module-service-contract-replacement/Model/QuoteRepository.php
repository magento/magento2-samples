<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleServiceContractReplacement\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;

class QuoteRepository extends \Magento\Quote\Model\QuoteRepository implements \Magento\Quote\Api\CartRepositoryInterface
{
    /**
     * Get active quote by id
     *
     * @param int $cartId
     * @param int[] $sharedStoreIds
     * @throws NoSuchEntityException
     * @return \Magento\Quote\Api\Data\CartInterface
     */
    public function get($cartId, array $sharedStoreIds = [])
    {
        $quote = parent::get($cartId, $sharedStoreIds);
        if (!$quote->getIsActive()) {
            throw NoSuchEntityException::singleField('cartId', $cartId);
        }
        return $quote;
    }
}
