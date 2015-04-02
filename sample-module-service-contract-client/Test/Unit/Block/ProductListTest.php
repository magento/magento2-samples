<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
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

    public function testGetProductTypesWithoutFilter()
    {
        $expectedResult = [
            'ProductType1Name' => [
                'label' => 'ProductType1Label',
                'is_active' => false,
            ],
            'ProductType2Name' => [
                'label' => 'ProductType2Label',
                'is_active' => false,
            ]
        ];
        $productTypeOne = $this->createProductType('ProductType1Name', 'ProductType1Label');
        $productTypeTwo = $this->createProductType('ProductType2Name', 'ProductType2Label');
        $this->productTypeList->expects($this->once())
            ->method('getProductTypes')
            ->willReturn([$productTypeOne, $productTypeTwo]);
        $result = $this->block->getProductTypes();
        $this->assertEquals($expectedResult, $result);
    }

    public function testGetProductTypesWithFilter()
    {
        $expectedResult = [
            'ProductType1Name' => [
                'label' => 'ProductType1Label',
                'is_active' => false,
            ],
            'ProductType2Name' => [
                'label' => 'ProductType2Label',
                'is_active' => true,
            ],
            'ProductType3Name' => [
                'label' => 'ProductType3Label',
                'is_active' => false,
            ]
        ];
        $this->request->expects($this->exactly(3))
            ->method('getParam')
            ->with($this->equalTo('type'))
            ->willReturn('ProductType2Name');
        $productTypeOne = $this->createProductType('ProductType1Name', 'ProductType1Label');
        $productTypeTwo = $this->createProductType('ProductType2Name', 'ProductType2Label');
        $productTypeThree = $this->createProductType('ProductType3Name', 'ProductType3Label');
        $this->productTypeList->expects($this->once())
            ->method('getProductTypes')
            ->willReturn([$productTypeOne, $productTypeTwo, $productTypeThree]);
        $result = $this->block->getProductTypes();
        $this->assertEquals($expectedResult, $result);
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
        $productType->expects($this->exactly(2))
            ->method('getName')
            ->willReturn($name);
        $productType->expects($this->once())
            ->method('getLabel')
            ->willReturn($label);
        return $productType;
    }
}
