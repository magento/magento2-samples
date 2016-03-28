<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleMessageQueue\Model\Handler\Async;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\GiftCard\Helper\Data as GiftCardData;
use Magento\Framework\Locale\CurrencyInterface as LocaleCurrency;

class SendCustomerNotification
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var GiftCardData
     */
    protected $giftCardData;

    /**
     * @var LocaleCurrency
     */
    protected $localeCurrency;

    /**
     * Initialize dependencies.
     *
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param CartRepositoryInterface $cartRepository
     * @param StoreManagerInterface $storeManager
     * @param GiftCardData $giftCardData
     * @param LocaleCurrency $localeCurrency
     */
    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        CartRepositoryInterface $cartRepository,
        StoreManagerInterface $storeManager,
        GiftCardData $giftCardData,
        LocaleCurrency $localeCurrency
    ) {
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
        $this->giftCardData = $giftCardData;
        $this->localeCurrency = $localeCurrency;
    }

    /**
     * Send customer notification
     *
     * @param string $payload
     * @throws \InvalidArgumentException
     * @return void
     */
    public function send($payload)
    {
        $payload = json_decode($payload, true);
        if (!isset($payload['cart_id'])) {
            throw new \InvalidArgumentException('Cart ID is required');
        }
        $quoteId = $payload['cart_id'];
        $quote = $this->cartRepository->get($quoteId);
        $store = $this->storeManager->getStore($quote->getStoreId());
        $storeName = $store->getName();

        if (!isset($payload['customer_email'])) {
            throw new \InvalidArgumentException('Customer email is required');
        }

        $transport = $this->transportBuilder->setTemplateIdentifier('giftcard_email_template')
            ->setTemplateOptions(
                ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $quote->getStoreId()]
            )
            ->setTemplateVars(
                [
                    'name' => isset($payload['customer_name']) ? $payload['customer_name'] : '',
                    'sender_name' => 'Your Friends at ' . $storeName,
                    'balance' => $this->getFormattedBalance(
                        isset($payload['amount']) ? $payload['amount'] : 0,
                        $quote->getStoreId()
                    ),
                    'giftcards' => $this->getCodeHtml($payload, $quote->getStoreId()),
                    'is_redeemable' => $payload['giftcard_is_redeemable'],
                    'store' => $store,
                    'store_name' => $storeName,
                    'is_multiple_codes' => 0
                ]
            )
            ->setFrom(['name' => 'Your Friends at ' . $storeName, 'email' => ''])
            ->addTo($payload['customer_email'])
            ->getTransport();
        $transport->sendMessage();

        $this->logger->debug('ASYNC Handler: Sent customer notification email to: ' . $payload['customer_email']);
    }

    /**
     * Return gift card code html
     *
     * @param array $payload
     * @param int $storeId
     * @return string
     */
    private function getCodeHtml(array $payload, $storeId)
    {
        return $this->giftCardData->getEmailGeneratedItemsBlock()->setCodes(
            [$payload['giftcard_code']]
        )->setArea(
            \Magento\Framework\App\Area::AREA_FRONTEND
        )->setIsRedeemable(
            $payload['giftcard_is_redeemable']
        )->setStore(
            $this->storeManager->getStore($storeId)
        )->toHtml();
    }

    /**
     * Return formatted Balance
     *
     * @param int|float $balance
     * @param int $storeId
     * @return string
     * @throws \Zend_Currency_Exception
     */
    private function getFormattedBalance($balance, $storeId)
    {
        return $this->localeCurrency->getCurrency(
            $this->storeManager->getStore($storeId)->getBaseCurrencyCode()
        )->toCurrency(
            $balance
        );
    }
}
