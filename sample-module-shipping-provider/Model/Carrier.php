<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleShippingProvider\Model;

use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Config;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;

/**
 * In-Store Pickup shipping model
 */
class Carrier extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'storepickup';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var MethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ErrorFactory $rateErrorFactory
     * @param LoggerInterface $logger
     * @param ResultFactory $rateResultFactory
     * @param MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * Get allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->getCarrierCode() => __($this->getConfigData('name'))];
    }

    /**
     * Collect and get rates
     *
     * @param \Magento\Framework\Object $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(\Magento\Framework\Object $request)
    {
        /**
         * Make sure that Shipping method is enabled
         */
        if (!$this->isActive()) {
            return false;
        }

        /**
         * Build Rate for each location
         * Each Rate displayed as shipping method under Carrier(In-Store Pickup) on frontend
         */
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();
        foreach($this->getLocations() as $locationId => $location) {
            $method = $this->buildRateForLocation($locationId, $location);
            $result->append($method);
        }

        return $result;
    }

    /**
     * Get location info for selected Shipping Method
     *
     * @param string $shippingMethod
     * @return array
     */
    public function getLocationInfo($shippingMethod)
    {
        /**
         * Extract location identifier
         * $shippingMethod = CarrierCode_Method i.e. storepickup_store_1
         */
        $locationId = str_replace($this->getCarrierCode() . '_', '', $shippingMethod);
        $locations = $this->getLocations();
        return array_key_exists($locationId, $locations) ? $locations[$locationId] : [];
    }

    /**
     * Build Rate based on location data
     *
     * @param string $locationId Shipping method(location) identifier
     * @param array $location Location info
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    protected function buildRateForLocation($locationId, array $location)
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $rateResultMethod */
        $rateResultMethod = $this->rateMethodFactory->create();
        $rateResultMethod->setCarrier($this->getCarrierCode());
        $carrierTitle = $this->getConfigData('title');
        $rateResultMethod->setCarrierTitle($carrierTitle);

        /**
         * Displayed as shipping method under Carrier(In-Store Pickup)
         */
        $methodTitle = sprintf('%s, %s, %s, %s (%s)',
            $location['street'],
            $location['city'],
            $location['country_id'],
            $location['postcode'],
            $location['message']
        );
        $rateResultMethod->setMethodTitle($methodTitle);
        $rateResultMethod->setMethod($locationId);

        $rateResultMethod->setPrice(0);
        $rateResultMethod->setCost(0);
        return $rateResultMethod;
    }

    /**
     * Get locations info
     *
     * @return array
     */
    protected function getLocations()
    {
        $locations = [];
        $configData = $this->getConfigData('store_locations');
        if (is_string($configData) && !empty($configData)) {
            $locations = unserialize($configData);
        }
        $shippingOrigin = $this->getShippingOrigin();

        $result = [];
        foreach ($locations as $location) {
            /**
             * Use location 'Title' as identifier of shipping method
             */
            $locationId = strtolower(str_replace(' ', '_', $location['title']));
            $result[$locationId] = [
                'title' => $location['title'],
                'street' => $location['street'],
                'phone' => $location['phone'],
                'message' => $location['message']
            ];
            $result[$locationId] = array_merge($result[$locationId], $shippingOrigin);
        }
        return $result;
    }

    /**
     * Get configured Store Shipping Origin
     *
     * @return array
     */
    protected function getShippingOrigin()
    {
        return [
            'country_id' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_COUNTRY_ID,
                ScopeInterface::SCOPE_STORE,
                $this->getStore()),
            'region_id' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_REGION_ID,
                ScopeInterface::SCOPE_STORE,
                $this->getStore()),
            'postcode' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_POSTCODE,
                ScopeInterface::SCOPE_STORE,
                $this->getStore()),
            'city' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_CITY,
                ScopeInterface::SCOPE_STORE,
                $this->getStore())
        ];
    }
}
