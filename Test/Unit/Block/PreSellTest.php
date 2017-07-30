<?php

namespace Space48\PreSell\Block;

use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Model\StockRegistry;

class PreSellTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PHPUnit_Framework_MockObject_MockObject | \Space48\PreSell\Block\PreSell
     */
    public $preSell;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | \Magento\Catalog\Model\Product
     */
    public $productMock;
    public $stockItemRepositoryMock;

    public function setUp()
    {
        $this->stockItemRepositoryMock = $this->getMockBuilder(StockRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* PreSell Class */
        /** @var \Space48\PreSell\Block\PreSell preSell */
        $this->preSell = new PreSell($this->stockItemRepositoryMock);

        /* Product Mock */
        $this->productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'getPreSell',
                'getPreSellQty',
                'getId',
                'getTypeId',
                'getData'
            ])
            ->getMock();

        $this->productMock->method('getId')->willReturn(1);
    }

    public function testReturnsTrueIfNotASimpleProduct()
    {
        $this->productMock->method('getQty')->willReturn(0);
        $this->productMock->method('getTypeId')->willReturn('configurable');
        $this->productMock->method('getPreSellQty')->willReturn(0);

        $this->assertTrue($this->preSell->isProductInStockOrAvailableForPreSale($this->productMock)
        );
    }

    public function testIsValidStockProductReturnsFalseIfProductIsNull()
    {
        $this->assertFalse($this->preSell->isValidStockProduct(null)
        );
    }

    public function testIsValidStockProductReturnsFalseIfProductHasNoTypeId()
    {
        $this->productMock->method('getTypeId')->willReturn(null);

        $this->assertFalse($this->preSell->isValidStockProduct($this->productMock)
        );
    }

    public function testIsValidStockProductReturnsTrueIfProductIsSimple()
    {
        $this->productMock->method('getData')->with('type_id')->willReturn('simple');

        $this->assertTrue($this->preSell->isValidStockProduct($this->productMock)
        );
    }

    public function testCanPreSellIfPreSellSetAndPreSellQtyAboveZero()
    {
        $this->productMock->expects($this->at(0))->method('getData')->with('pre_sell')->willReturn(true);
        $this->productMock->expects($this->at(1))->method('getData')->with('pre_sell_qty')->willReturn(34);

        $this->assertTrue($this->preSell->canPreSell($this->productMock));
    }

    public function testCanNotPreSellIfPreSellNotSetAndPreSellQtyAboveZero()
    {
        $this->productMock->method('getPreSellQty')->willReturn(34);
        $this->productMock->method('getPreSell')->willReturn(false);

        $this->assertFalse($this->preSell->canPreSell($this->productMock));
    }

    public function testCanNotPreSellIfPreSellNotSetAndPreSellQtyBelowZero()
    {
        $this->productMock->method('getPreSellQty')->willReturn(0);
        $this->productMock->method('getPreSell')->willReturn(false);

        $this->assertFalse($this->preSell->canPreSell($this->productMock));
    }

    public function testCanNotPreSellIfPreSellNotSet()
    {
        $this->productMock->method('getPreSell')->willReturn(null);

        $this->assertFalse($this->preSell->canPreSell($this->productMock));
    }
}
