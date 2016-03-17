<?php
/**
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractReplacement\Model;

use Magento\Framework\Exception\State\InvalidTransitionException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\CacheInterface;
use Magento\GiftMessage\Api\ItemRepositoryInterface;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Shopping cart gift message item repository object.
 */
class ItemRepository implements ItemRepositoryInterface
{
    /**
     * Cache key postfix
     */
    const CACHE_ID_POSTFIX = '_cart_item_gift_message';

    /**
     * Quote Item repository.
     *
     * @var CartItemRepositoryInterface
     */
    protected $quoteItemRepository;

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
     * @param CartItemRepositoryInterface $quoteItemRepository
     * @param QuoteRepository $quoteRepository
     * @param CacheInterface $cache
     */
    public function __construct(
        CartItemRepositoryInterface $quoteItemRepository,
        QuoteRepository $quoteRepository,
        CacheInterface $cache
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

        /** @var CartItemInterface $item */
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
     * @return CartItemInterface|null
     * @throws NoSuchEntityException
     */
    protected function getQuoteItem($cartId, $itemId)
    {
        $quoteItem = null;

        /** @var CartItemInterface[] $quoteItems */
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
