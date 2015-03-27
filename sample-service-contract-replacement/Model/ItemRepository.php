<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractReplacement\Model;

use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Shopping cart gift message item repository object.
 */
class ItemRepository implements \Magento\GiftMessage\Api\ItemRepositoryInterface
{
    /**
     * Cache key postfix
     */
    const CACHE_ID_POSTFIX = '_cart_item_gift_message';

    /**
     * Quote repository.
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * Cache
     *
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function get($cartId, $itemId)
    {
        $quote = $this->getQuote($cartId);
        $this->getQuoteItem($quote, $itemId);

        $msgCacheId = $itemId . self::CACHE_ID_POSTFIX;
        $giftMsg = $this->cache->load($msgCacheId);
        return unserialize($giftMsg);
    }

    /**
     * {@inheritDoc}
     */
    public function save($cartId, \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage, $itemId)
    {
        $quote = $this->getQuote($cartId);

        /** @var \Magento\Quote\Api\Data\CartItemInterface $item */
        $item = $this->getQuoteItem($quote, $itemId);
        if ($item->getProductType() == \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL) {
            throw new InvalidTransitionException(__('Gift Messages is not applicable for virtual products'));
        }

        $giftMessage->setCustomerId($quote->getCustomer()->getId());
        $giftMessage->setGiftMessageId(rand());

        $msgCacheId = $itemId . self::CACHE_ID_POSTFIX;
        $this->cache->save(serialize($giftMessage), $msgCacheId);

        return true;
    }

    /**
     * Get cart item
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @param int $itemId
     * @return \Magento\Quote\Api\Data\CartItemInterface|null
     * @throws NoSuchEntityException
     */
    protected function getQuoteItem($quote, $itemId)
    {
        $quoteItem = null;

        /** @var  \Magento\Quote\Api\Data\CartItemInterface[] $quoteItems */
        $quoteItems = $quote->getItems() ? $quote->getItems() : [];
        foreach ($quoteItems as $item) {
            if ($item->getItemId() == $itemId) {
                $quoteItem = $item;
                break;
            }
        }
        if (!$quoteItem) {
            throw new NoSuchEntityException(__('There is no item with provided id in the cart'));
        };
        return $quoteItem;
    }

    /**
     * Get cart
     *
     * @param int $cartId
     * @return \Magento\Quote\Api\Data\CartInterface
     * @throws NoSuchEntityException
     */
    protected function getQuote($cartId)
    {
        /** @var \Magento\Quote\Api\Data\CartInterface $quote */
        $quote = $this->quoteRepository->get($cartId);
        if (!$quote->getIsActive()) {
            throw NoSuchEntityException::singleField('cartId', $cartId);
        }
        return $quote;
    }
}
