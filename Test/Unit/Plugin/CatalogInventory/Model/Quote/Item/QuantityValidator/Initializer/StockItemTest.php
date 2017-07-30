<?php
/**
 * Space48_PreSell
 *
 * @category    Space48
 * @package     Space48_PreSell
 * @Date        07/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

namespace Space48\PreSell\Plugin\CatalogInventory\Model\Quote\Item\QuantityValidator\Initializer;

use Magento\CatalogInventory\Model\Stock\Item as StockItem;
use Magento\CatalogInventory\Model\StockStateProvider;
use Magento\Framework\DataObject;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Space48\PreSell\Plugin\CatalogInventory\Model\Quote\Item\QuantityValidator\Initializer\StockItemPlugin as StockItemPlugin;

class StockItemTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | StockStateProvider
     */
    private $mockStockStateProvider;

    /**
     * @var StockItemPlugin
     */
    private $stockItemPlugin;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | StockItem
     */
    private $mockStockItem;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject | QuoteItem
     */
    private $quoteItem;

    private $mockDataObject;

    public function setUp()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject | ManagerInterface $stubMessageManager */
        $stubMessageManager = $this->getMockBuilder(ManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->stockItemPlugin = new StockItemPlugin($stubMessageManager);

        $this->mockStockStateProvider = $this->getMockBuilder(StockStateProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockStockItem = $this->getMockBuilder(StockItem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteItem = $this->getMockBuilder(QuoteItem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockDataObject = $this->getMockBuilder(DataObject::class);
    }

    /**
     * @param $stockItem
     * @param $quoteItem
     * @param $qty
     *
     * @return mixed
     */
    public function __invoke($stockItem, $quoteItem, $qty)
    {
        return $this->mockDataObject;
    }

    public function testTheAroundMethodCanBeCalled()
    {
        $result = $this->callAroundInitializeMethod();
        $this->assertSame($this->mockDataObject, $result);
    }

    /**
     * @return DataObject
     */
    private function callAroundInitializeMethod()
    {
        $subject = $this->mockStockStateProvider;
        $proceed = $this;
        $stockItem = $this->mockStockItem;
        $quoteItem = $this->quoteItem;
        $qty = 1;

        return $this->stockItemPlugin->aroundInitialize($subject, $proceed, $stockItem, $quoteItem, $qty);
    }
}
