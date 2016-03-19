<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleMessageQueue\Model\Handler\Async;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use \Psr\Log\LoggerInterface;

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
     * Initialize dependencies.
     *
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param CartRepositoryInterface $cartRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        CartRepositoryInterface $cartRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Send customer notification
     *
     * @param array $payload
     * @throws \InvalidArgumentException
     * @return void
     */
    public function send(array $payload)
    {
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
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $quote->getStoreId()
                ]
            )
            ->setTemplateVars(
                [
                'name' => isset($payload['customer_name']) ? $payload['customer_name'] : '',
                'sender_name' => 'Your Friends at ' . $storeName,
                'balance' => '$' . isset($payload['amount']) ? $payload['amount'] : 0
                ]
            )
            ->setFrom(['name' => 'Your Friends at ' . $storeName, 'email' => ''])
            ->addTo($payload['customer_email'])
            ->getTransport();
        $transport->sendMessage();

        $this->logger->debug('ASYNC Handler: Sent customer notification email to: ' . $payload['customer_email']);
    }
}
