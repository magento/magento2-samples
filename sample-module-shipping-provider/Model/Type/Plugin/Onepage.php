<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleShippingProvider\Model\Type\Plugin;

use Magento\Checkout\Model\Type\Onepage as CheckoutOnePage;
use Magento\SampleShippingProvider\Model\Carrier;

/**
 * Change Shipping Address to selected Store location address
 */
class Onepage
{
    /**
     * @var Carrier
     */
    private $carrier;

    /**
     * @param Carrier $carrier
     */
    public function __construct(Carrier $carrier)
    {
        $this->carrier = $carrier;
    }

    /**
     * Replace shipping address with pickup location address
     *
     * @param CheckoutOnePage $subject
     * @param array $result
     * @return $this
     */
    public function afterSaveShippingMethod(CheckoutOnePage $subject, array $result)
    {
        if ($result) {
            return $result;
        }
        $quote = $subject->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingMethod = $shippingAddress->getShippingMethod();
        /**
         * In-Store pickup selected
         * Update Shipping Address
         */
        if (strpos($shippingMethod, $this->carrier->getCarrierCode()) !== false) {
            $locationAddress = $this->carrier->getLocationInfo($shippingMethod);
            $shippingAddress->setCountryId($locationAddress['country_id']);
            $shippingAddress->setRegionId($locationAddress['region_id']);
            $shippingAddress->setPostcode($locationAddress['postcode']);
            $shippingAddress->setCity($locationAddress['city']);
            $shippingAddress->setStreet($locationAddress['street']);
            $shippingAddress->setTelephone($locationAddress['phone']);
        }
        return $result;
    }
}
