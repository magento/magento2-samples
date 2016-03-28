<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleServiceContractClient\Block;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductTypeInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\ProductTypeListInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class ProductList
 */
class ProductList extends Template
{
    /**
     * @var ProductTypeListInterface
     */
    private $productTypeList;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @param Context $context
     * @param ProductTypeListInterface $productTypeList
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductTypeListInterface $productTypeList,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productTypeList = $productTypeList;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getProducts()
    {
        $filters = $this->buildFilters();
        $searchCriteria = $this->buildSearchCriteria($filters);
        return $this->productRepository->getList($searchCriteria);
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductTypeInterface[]
     */
    public function getProductTypes()
    {
        return $this->productTypeList->getProductTypes();
    }

    /**
     * @param ProductTypeInterface $productType
     * @return bool
     */
    public function isTypeActive(ProductTypeInterface $productType)
    {
        return $this->getType() === $productType->getName();
    }

    /**
     * @return string
     */
    private function getType()
    {
        return $this->getRequest()->getParam('type');
    }

    /**
     * @return \Magento\Framework\Api\Filter[]
     */
    private function buildFilters()
    {
        $filters = [];
        if ($this->getType()) {
            $typeFilter = $this->filterBuilder
                ->setField(ProductInterface::TYPE_ID)
                ->setValue($this->getType())
                ->create();
            $filters[] = $typeFilter;
        }
        return $filters;
    }

    /**
     * @param \Magento\Framework\Api\Filter[] $filters
     * @return \Magento\Framework\Api\SearchCriteriaInterface
     */
    private function buildSearchCriteria(array $filters)
    {
        return $this->searchCriteriaBuilder->addFilter($filters)->create();
    }
}
