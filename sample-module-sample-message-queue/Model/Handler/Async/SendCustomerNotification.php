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
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * Initialize dependencies.
     *
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param CartRepositoryInterface $cartRepository
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        CartRepositoryInterface $cartRepository,
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $appState
    ) {
        $this->logger = $logger;
        $this->transportBuilder = $transportBuilder;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
        $this->appState = $appState;
        $this->appState->setAreaCode('global');
    }

    public function send($quoteId)
    {
        $quote = $this->cartRepository->get($quoteId);
        $storeName = $this->storeManager->getStore($quote->getStoreId())->getName();
        $customer = $quote->getCustomer();
        if (!$customer->getId()) {
            $this->logger->debug('ASYNC Handler: Not a registered customer. No notification email sent.');
            return;
        }
        $customerEmail = $customer->getEmail();
        $customerName = $customer->getFirstname() . ' ' . $customer->getLastname();

        $transport = $this->transportBuilder->setTemplateIdentifier('giftcard_email_template')
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $quote->getStoreId()])
            ->setTemplateVars([
                'name' => $customerName,
                'sender_name' => 'Your Friends at ' . $storeName,
                'balance' => '$5.00'
            ])
            ->setFrom(['name' => 'Your Friends at ' . $storeName, 'email' => ''])
            ->addTo($customerEmail)
            ->getTransport();
        $transport->sendMessage();

        $this->logger->debug('ASYNC Handler: Sent customer notification email to: ' . $customerEmail);
    }
}