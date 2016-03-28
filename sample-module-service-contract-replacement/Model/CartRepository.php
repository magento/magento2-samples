<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractReplacement\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\GiftMessage\Api\CartRepositoryInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Shopping cart gift message repository object.
 */
class CartRepository implements CartRepositoryInterface
{
    /**
     * Cache key postfix
     */
    const CACHE_ID_POSTFIX = '_cart_gift_message';

    /**
     * Quote repository.
     *
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * Cache
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @param QuoteRepository $quoteRepository
     * @param CacheInterface $cache
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        CacheInterface $cache
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
        /** @var CartInterface $quote */
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
