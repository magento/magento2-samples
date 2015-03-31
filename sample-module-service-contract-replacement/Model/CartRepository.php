<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractReplacement\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\State\InvalidTransitionException;

/**
 * Shopping cart gift message repository object.
 */
class CartRepository implements \Magento\GiftMessage\Api\CartRepositoryInterface
{
    /**
     * Cache key postfix
     */
    const CACHE_ID_POSTFIX = '_cart_gift_message';

    /**
     * Quote repository.
     *
     * @var \Magento\SampleServiceContractReplacement\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * Cache
     *
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @param QuoteRepository $quoteRepository
     * @param \Magento\Framework\App\CacheInterface $cache
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function get($cartId)
    {
        $this->quoteRepository->get($cartId);
        $msgCacheId = $cartId . self::CACHE_ID_POSTFIX;
        $giftMsg = $this->cache->load($msgCacheId);
        if (!$giftMsg) {
            throw new NoSuchEntityException(__('There is no gift message in the cart with provided id'));
        };
        return unserialize($giftMsg);
    }

    /**
     * {@inheritDoc}
     */
    public function save($cartId, \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage)
    {
        /** @var \Magento\Quote\Api\Data\CartInterface $quote */
        $quote = $this->quoteRepository->get($cartId);
        if (0 == $quote->getItemsCount()) {
            throw new InputException(__('Gift Messages is not applicable for empty cart'));
        }

        if ($quote->getIsVirtual()) {
            throw new InvalidTransitionException(__('Gift Messages is not applicable for virtual products'));
        }

        $giftMessage->setCustomerId($quote->getCustomer()->getId());
        $giftMessage->setGiftMessageId(rand());

        $msgCacheId = $cartId . self::CACHE_ID_POSTFIX;
        $this->cache->save(serialize($giftMessage), $msgCacheId);

        return true;
    }
}
