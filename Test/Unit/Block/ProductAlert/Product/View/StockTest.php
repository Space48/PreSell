<?php

namespace Space48\PreSell\Block\ProductAlert\Product\View;

class StockTest extends \PHPUnit_Framework_TestCase
{

    private $block;

    public function setUp()
    {
        $mockContext = $this->getMockBuilder(\Magento\Framework\View\Element\Template\Context::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockProductAlertHelper = $this->getMockBuilder(\Magento\ProductAlert\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistry = $this->getMockBuilder(\Magento\Framework\Registry::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPostHelper = $this->getMockBuilder(\Magento\Framework\Data\Helper\PostHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockPresellBlock = $this->getMockBuilder(\Space48\PreSell\Block\PreSell::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->block = new Stock($mockContext, $mockProductAlertHelper, $mockRegistry, $mockPostHelper, $mockPresellBlock);
    }

    public function testStockBlockExtendView()
    {
        $this->assertInstanceOf(\Magento\ProductAlert\Block\Product\View::class, $this->block);
    }

}
