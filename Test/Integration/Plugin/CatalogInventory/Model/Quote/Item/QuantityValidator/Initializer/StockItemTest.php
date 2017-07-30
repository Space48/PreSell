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

use Magento\CatalogInventory\Model\Quote\Item\QuantityValidator\Initializer\StockItem as OriginalStockItem;
use Magento\TestFramework\Interception\PluginList;
use Magento\TestFramework\ObjectManager;

class StockItemTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();
    }

    public function testPluginInterceptCallsToStockItem()
    {
        $pluginInfo = $this->getStockItemPluginInfo();

        $this->assertSame(StockItemPlugin::class, $pluginInfo['Space48_PreSell::StockItem']['instance']);
    }

    /**
     * @return array[]
     */
    private function getStockItemPluginInfo()
    {
        /** @var PluginList $pluginList */
        $pluginList = $this->objectManager->create(PluginList::class);

        return $pluginList->get(OriginalStockItem::class, []);
    }
}
