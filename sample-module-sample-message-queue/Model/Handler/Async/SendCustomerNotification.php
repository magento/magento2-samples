<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
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
     * @param $payload
     */
    public function send($payload)
    {
        $quoteId = $payload['cart_id'];
        $quote = $this->cartRepository->get($quoteId);
        $store = $this->storeManager->getStore($quote->getStoreId());
        $storeName = $store->getName();

        $transport = $this->transportBuilder->setTemplateIdentifier('giftcard_email_template')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $quote->getStoreId()])
            ->setTemplateVars([
                'name' => $payload['customer_name'],
                'sender_name' => 'Your Friends at ' . $storeName,
                'balance' => '$' . $payload['amount']
            ])
            ->setFrom(['name' => 'Your Friends at ' . $storeName, 'email' => ''])
            ->addTo($payload['customer_email'])
            ->getTransport();
        $transport->sendMessage();
        $this->logger->debug('ASYNC Handler: Sent customer notification email to: ' . $payload['customer_email']);
    }
}