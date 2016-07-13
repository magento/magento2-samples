<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\Catalog\Api\Data\ProductExtension;
use Magento\Catalog\Api\Data\ProductExtensionInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ExternalLinks\Api\Data\ExternalLinkInterface;
use Magento\ExternalLinks\Model\ExternalLink;

\Magento\TestFramework\Helper\Bootstrap::getInstance()->reinitialize();

/** @var \Magento\TestFramework\ObjectManager $objectManager */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var ProductRepositoryInterface $productRepository */
$productRepository = $objectManager->get(ProductRepositoryInterface::class);
/** @var $product \Magento\Catalog\Model\Product */
$product = $objectManager->create(Magento\Catalog\Model\Product::class);
$dynamicProductData = [
    [
        'id' => 1,
        'sku' => 'first-product',
        'ebay_link' => 'http://ebay.com/some-address-1',
        'amazon_link' => 'http://amazon.com/some-address-1'
    ]
];

foreach ($dynamicProductData as $dynamicData) {
    /** @var ProductExtensionInterface $productExtensionAttributes */
    $productExtensionAttributes = $objectManager->create(ProductExtension::class);
    /** @var ExternalLinkInterface $externalLink */
    $externalLink = $objectManager->create(ExternalLink::class);
    $externalLink->setLink($dynamicData['ebay_link']);
    $externalLink->setProductId($dynamicData['id']);
    $externalLink->setLinkType('ebay');

    $externalLinks[] = $externalLink;

    $externalLink = $objectManager->create(ExternalLink::class);
    $externalLink->setLink($dynamicData['amazon_link']);
    $externalLink->setProductId($dynamicData['id']);
    $externalLink->setLinkType('amazon');

    $externalLinks[] = $externalLink;

    $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
        ->setId($dynamicData['id'])
        ->setAttributeSetId(4)
        ->setWebsiteIds([1])
        ->setName('Simple Product')
        ->setSku($dynamicData['sku'])
        ->setPrice(10)
        ->setUrlKey('simple-product-' . $dynamicData['id'])
        ->setWeight(1)
        ->setShortDescription('Short description')
        ->setTaxClassId(0)
        ->setTierPrice(
            [
                [
                    'website_id' => 0,
                    'cust_group' => \Magento\Customer\Model\Group::CUST_GROUP_ALL,
                    'price_qty'  => 2,
                    'price'      => 8,
                ],
                [
                    'website_id' => 0,
                    'cust_group' => \Magento\Customer\Model\Group::CUST_GROUP_ALL,
                    'price_qty'  => 5,
                    'price'      => 5,
                ],
                [
                    'website_id' => 0,
                    'cust_group' => \Magento\Customer\Model\Group::NOT_LOGGED_IN_ID,
                    'price_qty'  => 3,
                    'price'      => 5,
                ],
            ]
        )
        ->setDescription('Description with <b>html tag</b>')
        ->setMetaTitle('meta title')
        ->setMetaKeyword('meta keyword')
        ->setMetaDescription('meta description')
        ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
        ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
        ->setStockData(
            [
                'use_config_manage_stock'   => 1,
                'qty'                       => 100,
                'is_qty_decimal'            => 0,
                'is_in_stock'               => 1,
            ]
        )->setCanSaveCustomOptions(false)
        ->setHasOptions(false);

    $productExtensionAttributes->setExternalLinks($externalLinks);
    $product->setExtensionAttributes($productExtensionAttributes);
    $productRepository->save($product);
}


