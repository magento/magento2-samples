<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleServiceContractReplacement\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;

class QuoteRepository extends \Magento\Quote\Model\QuoteRepository implements CartRepositoryInterface
{
    /**
     * Get active quote by id
     *
     * @param int $cartId
     * @param int[] $sharedStoreIds
     * @throws NoSuchEntityException
     * @return CartInterface
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
