<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractClient\Test\Unit\Block;


class ProductListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\View\Element\Template\Context
     */
    private $context;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Catalog\Api\ProductTypeListInterface
     */
    private $productTypeList;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var \Magento\SampleServiceContractClient\Block\ProductList
     */
    private $block;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->context = $this->getMockBuilder('Magento\Framework\View\Element\Template\Context')
            ->disableOriginalConstructor()
            ->setMethods(['getRequest'])
            ->getMock();
        $this->request = $this->getMockBuilder('Magento\Framework\App\RequestInterface')
            ->getMockForAbstractClass();
        $this->context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->productTypeList = $this->getMockBuilder('Magento\Catalog\Api\ProductTypeListInterface')
            ->getMockForAbstractClass();
        $this->productRepository = $this->getMockBuilder('Magento\Catalog\Api\ProductRepositoryInterface')
            ->getMockForAbstractClass();
        $this->searchCriteriaBuilder = $this->getMockBuilder('Magento\Framework\Api\SearchCriteriaBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->filterBuilder = $this->getMockBuilder('Magento\Framework\Api\FilterBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $this->block = $objectManager->getObject(
            'Magento\SampleServiceContractClient\Block\ProductList',
            [
                'context' => $this->context,
                'productTypeList' => $this->productTypeList,
                'productRepository' => $this->productRepository,
                'searchCriteriaBuilder' => $this->searchCriteriaBuilder,
                'filterBuilder' => $this->filterBuilder,
            ]
        );

    }

    public function testGetProductTypes()
    {
        $productTypeOne = $this->createProductType('ProductType1Name', 'ProductType1Label');
        $productTypeTwo = $this->createProductType('ProductType2Name', 'ProductType2Label');
        $this->productTypeList->expects($this->once())
            ->method('getProductTypes')
            ->willReturn([$productTypeOne, $productTypeTwo]);
        $expectedResult = [
            $productTypeOne,
            $productTypeTwo
        ];
        $result = $this->block->getProductTypes();
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @param $requestedType
     * @param $productType
     * @param $expectedValue
     * @dataProvider isTypeActiveDataProvider
     */
    public function testIsTypeActive($requestedType, $productType, $expectedValue)
    {
        $this->request->expects($this->exactly(1))
            ->method('getParam')
            ->with($this->equalTo('type'))
            ->willReturn($requestedType);
        $this->assertEquals($expectedValue, $this->block->isTypeActive($productType));
    }

    public function isTypeActiveDataProvider()
    {
        return [
            'activeType' => [
                'requestedType' => 'FilteredProductType',
                'productType' => $this->createProductType('FilteredProductType', 'FilteredProductTypeLabel'),
                'expectedValue' => true,
            ],
            'notActiveType' => [
                'requestedType' => 'FilteredProductType',
                'productType' => $this->createProductType('ExampleProductType', 'FilteredProductTypeLabel'),
                'expectedValue' => false,
            ]

        ];
    }

    public function testGetProductsWithoutFilter()
    {
        $products = ['Product1', 'Product2'];
        $searchCriteria = $this->getMockBuilder('Magento\Framework\Api\SearchCriteriaInterface')
            ->getMockForAbstractClass();
        $this->searchCriteriaBuilder->expects($this->once())
            ->method('addFilter')
            ->with($this->equalTo([]))
            ->willReturnSelf();
        $this->searchCriteriaBuilder->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);
        $this->productRepository->expects($this->once())
            ->method('getList')
            ->with($searchCriteria)
            ->willReturn($products);

        $result = $this->block->getProducts();
        $this->assertEquals($products, $result);
    }

    public function testGetProductsWithFilter()
    {
        $products = ['Product1', 'Product2'];
        $searchCriteria = $this->getMockBuilder('Magento\Framework\Api\SearchCriteriaInterface')
            ->getMockForAbstractClass();
        $this->request->expects($this->exactly(2))
            ->method('getParam')
            ->with($this->equalTo('type'))
            ->willReturn('FilterProductType');
        $this->filterBuilder->expects($this->once())
            ->method('setField')
            ->with('type_id')
            ->willReturnSelf();
        $this->filterBuilder->expects($this->once())
            ->method('setValue')
            ->with($this->equalTo('FilterProductType'))
            ->willReturnSelf();
        $filter = $this->getMockBuilder('Magento\Framework\Api\Filter')
            ->getMock();
        $this->filterBuilder->expects($this->once())
            ->method('create')
            ->willReturn($filter);
        $this->searchCriteriaBuilder->expects($this->once())
            ->method('addFilter')
            ->with($this->equalTo([$filter]))
            ->willReturnSelf();
        $this->searchCriteriaBuilder->expects($this->once())
            ->method('create')
            ->willReturn($searchCriteria);
        $this->productRepository->expects($this->once())
            ->method('getList')
            ->with($searchCriteria)
            ->willReturn($products);

        $result = $this->block->getProducts();
        $this->assertEquals($products, $result);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createProductType($name, $label)
    {
        $productType = $this->getMockBuilder('Magento\Catalog\Api\Data\ProductTypeInterface')
            ->setMethods(['getName', 'getLabel'])
            ->getMockForAbstractClass();
        $productType->expects($this->any())
            ->method('getName')
            ->willReturn($name);
        $productType->expects($this->any())
            ->method('getLabel')
            ->willReturn($label);
        return $productType;
    }
}
