<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleShippingProvider\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Config;
use Magento\Shipping\Model\Rate\ResultFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\Method;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Psr\Log\LoggerInterface;

/**
 * Class Carrier In-Store Pickup shipping model
 */
class Carrier extends AbstractCarrier implements CarrierInterface
{
    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code = 'storepickup';

    /**
     * Whether this carrier has fixed rates calculation
     *
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
     * Generates list of allowed carrier`s shipping methods
     * Displays on cart price rules page
     *
     * @return array
     * @api
     */
    public function getAllowedMethods()
    {
        return [$this->getCarrierCode() => __($this->getConfigData('name'))];
    }

    /**
     * Collect and get rates for storefront
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param RateRequest $request
     * @return DataObject|bool|null
     * @api
     */
    public function collectRates(RateRequest $request)
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
        $result = $this->rateResultFactory->create();
        foreach ($this->getLocations() as $locationId => $location) {
            $method = $this->buildRateForLocation($locationId, $location);
            $result->append($method);
        }

        return $result;
    }

    /**
     * Build Rate based on location data
     *
     * @param string $locationId Shipping method(location) identifier
     * @param array $location Location info
     * @return Method
     */
    protected function buildRateForLocation($locationId, array $location)
    {
        $rateResultMethod = $this->rateMethodFactory->create();
        /**
         * Set carrier's method data
         */
        $rateResultMethod->setData('carrier', $this->getCarrierCode());
        $rateResultMethod->setData('carrier_title', $this->getConfigData('title'));

        /**
         * Displayed as shipping method under Carrier(In-Store Pickup)
         */
        $methodTitle = sprintf(
            '%s, %s, %s, %s (%s)',
            $location['street'],
            $location['city'],
            $location['country_id'],
            $location['postcode'],
            $location['message']
        );
        $rateResultMethod->setData('method_title', $methodTitle);
        $rateResultMethod->setData('method', $locationId);

        $rateResultMethod->setPrice(10);
        $rateResultMethod->setData('cost', 10);

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
        /**
         * Get Shipping origin data from store scope config
         * Displays data on storefront
         */
        return [
            'country_id' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_COUNTRY_ID,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            ),
            'region_id' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_REGION_ID,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            ),
            'postcode' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_POSTCODE,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            ),
            'city' => $this->_scopeConfig->getValue(
                Config::XML_PATH_ORIGIN_CITY,
                ScopeInterface::SCOPE_STORE,
                $this->getData('store')
            )
        ];
    }
}
