<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleMessageQueue\Model;

use Magento\Checkout\Model\Cart;

class AddToCartPlugin
{
    /**
     * @var \Magento\Framework\MessageQueue\PublisherPool
     */
    protected $publisherPool;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;


    public function __construct(
        \Magento\Framework\MessageQueue\PublisherPool $publisherPool,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->publisherPool = $publisherPool;
        $this->logger = $logger;
    }

    /**
     * @param Cart $subject
     * @param \Closure $proceed
     * @return Cart
     */
    public function aroundSave(
        Cart $subject,
        \Closure $proceed
    ) {
        $before = $subject->getItemsQty();
        $result = $proceed();
        $after = $subject->getItemsQty();

        if ($subject->getQuote()->getCustomerId() && $before == 0 && $after > $before) {
            $this->logger->debug('Plugin Start: Before items QTY: ' . $before . '; After Items QTY: ' . $after);
            $syncRequestResult = $this->publisherPool
                ->getByTopicType('add.to.cart.product.added')
                ->publish('add.to.cart.product.added', $subject->getQuote()->getId());

            $this->publisherPool
                ->getByTopicType('add.to.cart.giftcard.added')
                ->publish('add.to.cart.giftcard.added', $syncRequestResult);

            $this->publisherPool
                ->getByTopicType('add.to.cart.giftcard.added.success')
                ->publish('add.to.cart.giftcard.added.success', ['amount', 'customer email', 'cart id']);

            $this->logger->debug('Plugin End');
        } else {
            //Just for debugging
            $this->logger->debug('Plugin: do nothing. ' . $before .' != 0 :');
        }
        return $result;
    }

}