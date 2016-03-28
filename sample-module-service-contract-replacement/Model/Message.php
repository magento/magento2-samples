<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleServiceContractReplacement\Model;

use Magento\GiftMessage\Api\Data\MessageInterface;
use Magento\GiftMessage\Api\Data\MessageExtensionInterface;

/**
 * Gift Message model
 */
class Message implements MessageInterface
{
    /**
     * Data storage
     *
     * @var array
     */
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function getGiftMessageId()
    {
        return $this->getData(self::GIFT_MESSAGE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setGiftMessageId($id)
    {
        return $this->setData(self::GIFT_MESSAGE_ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId($id)
    {
        return $this->setData(self::CUSTOMER_ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getSender()
    {
        return $this->getData(self::SENDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setSender($sender)
    {
        return $this->setData(self::SENDER, $sender);
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipient()
    {
        return $this->getData(self::RECIPIENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setRecipient($recipient)
    {
        return $this->setData(self::RECIPIENT, $recipient);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(MessageExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * Set model data
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Set model data
     *
     * @param $key
     * @return mixed|null
     */
    protected function getData($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
}
