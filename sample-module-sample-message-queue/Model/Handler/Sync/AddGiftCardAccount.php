<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleMessageQueue\Model\Handler\Sync;

use Psr\Log\LoggerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\GiftCardAccount\Model\Giftcardaccount;

class AddGiftCardAccount
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\GiftCardAccount\Model\GiftcardaccountFactory
     */
    protected $giftCardAccountFactory;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Initialize dependencies.
     *
     * @param LoggerInterface $logger
     * @param \Magento\GiftCardAccount\Model\GiftcardaccountFactory $giftCardAccountFactory
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        LoggerInterface $logger,
        \Magento\GiftCardAccount\Model\GiftcardaccountFactory $giftCardAccountFactory,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $appState
    ) {
        $this->logger = $logger;
        $this->giftCardAccountFactory = $giftCardAccountFactory;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
    }


    /**
     * Add Gift Card Account to Shopping Card
     *
     * @param int $quoteId
     * @return string
     * @throws \Exception
     */
    public function add($quoteId)
    {
        $giftCardCode = 'GIFT_CARD_ACCOUNT_SAMPLE_' . rand();
        try {
            $quote = $this->cartRepository->get($quoteId);
            $websiteId = $this->storeManager->getStore($quote->getStoreId())->getWebsiteId();

            $this->logger->debug(
                'SYNC Handler: Add gift card #' . $giftCardCode . ' to customer shopping cart #' . $quoteId
            );

            /** @var Giftcardaccount $giftCardAccount */
            $giftCardAccount = $this->giftCardAccountFactory->create();
            $giftCardAccount->setCode($giftCardCode);
            $giftCardAccount->setStatus(Giftcardaccount::STATUS_ENABLED);
            $giftCardAccount->setState(Giftcardaccount::STATE_AVAILABLE);
            $giftCardAccount->setWebsiteId($websiteId);
            $giftCardAccount->setIsRedeemable(Giftcardaccount::REDEEMABLE);
            $giftCardAccount->setBalance(5);
            $giftCardAccount->setDateExpires(date('Y-m-d', strtotime('+1 week')));
            $giftCardAccount->save();
            $giftCardAccount->addToCart(true, $quote);
        } catch (\Exception $e) {
            $this->logger->debug('Handler: ' . __METHOD__ . '. Error: '. $e->getMessage());
            throw $e;
        }
        return $giftCardCode;
    }
}
