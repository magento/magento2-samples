<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\Model\Data;


use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\SampleServiceContractNew\API\Data\FeedInterface;

class Feed extends AbstractExtensibleObject implements FeedInterface
{
    /**
     * @return string
     */
    public function getId()
    {
        return $this->_get(self::KEY_ID);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_get(self::KEY_TITLE);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_get(self::KEY_DESCRIPTION);
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->_get(self::KEY_LINK);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setLink($value)
    {
        $this->setData(self::KEY_LINK, $value);
        return $this;
    }

}
