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

namespace Space48\PreSell\Plugin\ProductAlert\Controller\Add;

use Magento\ProductAlert\Controller\Add\Stock as OriginalStock;
use Magento\TestFramework\Interception\PluginList;
use Magento\TestFramework\ObjectManager;

class StockTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();

    }

    public function testPluginInterceptCallsToStock()
    {
        $pluginInfo = $this->getPluginStockInfo();

        $this->assertSame(Stock::class, $pluginInfo['Space48_PreSell::Stock']['instance']);
    }

    /**
     * @return array[]
     */
    private function getPluginStockInfo()
    {
        /** @var PluginList $pluginList */
        $pluginList = $this->objectManager->get(PluginList::class);

        return $pluginList->get(OriginalStock::class, []);
    }
}
