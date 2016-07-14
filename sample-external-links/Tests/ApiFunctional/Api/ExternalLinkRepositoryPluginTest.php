<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExternalLinks\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Store\Model\Store;
use Magento\TestFramework\Authentication\OauthHelper;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class ExternalLinkRepositoryInterfaceTest
 * @package Magento\ExternalLinks\Api
 * @magentoAppIsolation enabled
 */
class ExternalLinkRepositoryPluginTest extends WebapiAbstract
{
    const SERVICE_NAME = 'catalogProductRepositoryV1';
    const SERVICE_VERSION = 'V1';
    const RESOURCE_PATH = '/V1/products';

    public function setUp()
    {
        OauthHelper::clearApiAccessCredentials(); //clearing Api credentials in order to generate new one for each test
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testGetListWithExternalLinks()
    {
        require_once __DIR__ . '/../_files/one_simple_product.php';
        $dynamicData = $this->getDynamicProductData();
        $searchCriteria = [
            'searchCriteria' => [
                'current_page' => 1
            ]
        ]; //take all products we have

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'GetList',
            ],
        ];

        $response = $this->_webApiCall($serviceInfo, $searchCriteria);

        $this->assertArrayHasKey('search_criteria', $response);
        $this->assertArrayHasKey('total_count', $response);
        $this->assertArrayHasKey('items', $response);

        foreach ($response['items'] as $item) {
            $this->assertArrayHasKey('extension_attributes', $item);
            $this->assertArrayHasKey('sku', $item);
            $this->assertArrayHasKey('external_links', $item['extension_attributes']);

            foreach($item['extension_attributes']['external_links'] as $link) {
                if (isset($dynamicData[$item['sku']])) {
                    $this->assertArrayHasKey('product_id', $link);
                    $this->assertArrayHasKey('link', $link);
                    $this->assertArrayHasKey('link_type', $link);
                    $this->assertEquals(
                        $link['link'],
                        $dynamicData[$item['sku']][$link['link_type'] . '_link']
                    );
                }
            }
        }
    }

    /**
     * Get Simple Product Data
     *
     * @param array $productData
     * @return array
     */
    protected function getSimpleProductData($productData = [])
    {
        return [
            ProductInterface::SKU => isset($productData[ProductInterface::SKU])
                ? $productData[ProductInterface::SKU] : 'third-product',
            ProductInterface::NAME => isset($productData[ProductInterface::NAME])
                ? $productData[ProductInterface::NAME] : uniqid('Third Product', true),
            ProductInterface::VISIBILITY => 4,
            ProductInterface::TYPE_ID => 'simple',
            ProductInterface::PRICE => 3.62,
            ProductInterface::STATUS => 1,
            ProductInterface::ATTRIBUTE_SET_ID => 4,
            'extension_attributes' => [
                'external_links' => [
                    [
                        'link' => 'http://ebay.com/some-address-3',
                        'link_type' => 'ebay'
                    ],
                    [
                        'link' => 'http://amazon.com/some-address-3',
                        'link_type' => 'amazon'
                    ]
                ]
            ]
        ];
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testSaveWithExternalLinks()
    {
        $productData = $this->getSimpleProductData();
        $dynamicResponseData = [
            'sku' => 'third-product',
            'ebay_link' => 'http://ebay.com/some-address-3',
            'amazon_link' => 'http://amazon.com/some-address-3'
        ];
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        $product = $this->_webApiCall($serviceInfo, ["product" => $productData, 'XDEBUG_SESSION_START' => 'PHPSTORM']);

        $this->assertArrayHasKey('id', $product);
        $this->assertArrayHasKey('extension_attributes', $product);

        $this->assertArrayHasKey('external_links', $product['extension_attributes']);
        $externalLinks = $product['extension_attributes']['external_links'];

        foreach($externalLinks as $link) {
            $this->assertArrayHasKey('product_id', $link);
            $this->assertArrayHasKey('link', $link);
            $this->assertArrayHasKey('link_type', $link);
            $this->assertEquals(
                $link['link'],
                $dynamicResponseData[$link['link_type'] . '_link']
            );
        }
    }

    /**
     * @param $sku
     * @return array|bool|float|int|string
     */
    private function getProductBySku($sku) {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $sku,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_NAME . 'Get',
            ],
        ];

        return $this->_webApiCall($serviceInfo, ['sku' => $sku]);
    }

    /**
     * @magentoDbIsolation enabled
     */
    public function testGetWithExternalLinks()
    {
        require_once __DIR__ . '/../_files/two_simple_products.php';
        $productData = [
            'id' => 1,
            'sku' => 'first-product',
            'ebay_link' => 'http://ebay.com/some-address-1',
            'amazon_link' => 'http://amazon.com/some-address-1'
        ];
        $response = $this->getProductBySku($productData['sku']);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('extension_attributes', $response);

        $this->assertArrayHasKey('external_links', $response['extension_attributes']);
        $externalLinks = $response['extension_attributes']['external_links'];

        foreach($externalLinks as $link) {
            $this->assertArrayHasKey('product_id', $link);
            $this->assertArrayHasKey('link', $link);
            $this->assertArrayHasKey('link_type', $link);
            $this->assertEquals(
                $link['link'],
                $productData[$link['link_type'] . '_link']
            );
        }
    }

    private function getDynamicProductData()
    {
        return [
            'first-product' => [
                'id' => 1,
                'sku' => 'first-product',
                'ebay_link' => 'http://ebay.com/some-address-1',
                'amazon_link' => 'http://amazon.com/some-address-1'
            ],
            'second-product' => [
                'id' => 2,
                'sku' => 'second-product',
                'ebay_link' => 'http://ebay.com/some-address-2',
                'amazon_link' => 'http://amazon.com/some-address-2'
            ]
        ];
    }
}
