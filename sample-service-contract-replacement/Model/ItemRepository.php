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
     * Quote Item repository.
     *
     * @var \Magento\Quote\Api\CartItemRepositoryInterface
     */
    protected $quoteItemRepository;

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
     * @param \Magento\Quote\Api\CartItemRepositoryInterface $quoteItemRepository
     * @param QuoteRepository $quoteRepository
     * @param \Magento\Framework\App\CacheInterface $cache
     */
    public function __construct(
        \Magento\Quote\Api\CartItemRepositoryInterface $quoteItemRepository,
        QuoteRepository $quoteRepository,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->quoteItemRepository = $quoteItemRepository;
        $this->quoteRepository = $quoteRepository;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function get($cartId, $itemId)
    {
        $this->getQuoteItem($cartId, $itemId);

        $msgCacheId = $itemId . self::CACHE_ID_POSTFIX;
        $giftMsg = $this->cache->load($msgCacheId);
        if (!$giftMsg) {
            throw new NoSuchEntityException(__('There is no gift message for item with provided id in the cart'));
        };
        return unserialize($giftMsg);
    }

    /**
     * {@inheritDoc}
     */
    public function save($cartId, \Magento\GiftMessage\Api\Data\MessageInterface $giftMessage, $itemId)
    {
        $quote = $this->quoteRepository->get($cartId);

        /** @var \Magento\Quote\Api\Data\CartItemInterface $item */
        $item = $this->getQuoteItem($cartId, $itemId);
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
     * @param int $cartId
     * @param int $itemId
     * @return \Magento\Quote\Api\Data\CartItemInterface|null
     * @throws NoSuchEntityException
     */
    protected function getQuoteItem($cartId, $itemId)
    {
        $quoteItem = null;

        /** @var \Magento\Quote\Api\Data\CartItemInterface[] $quoteItems */
        $quoteItems = $this->quoteItemRepository->getList($cartId);
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
}
