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

namespace Space48\PreSell\Plugin\GroupedProduct\Model\Product\Type;

use Magento\GroupedProduct\Model\Product\Type\Grouped as OriginalGrouped;
use Magento\TestFramework\Interception\PluginList;
use Magento\TestFramework\ObjectManager;

class GroupedTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();

    }

    public function testPluginInterceptCallsToGrouped()
    {
        $pluginInfo = $this->getGroupedPluginInfo();

        $this->assertSame(GroupedPlugin::class, $pluginInfo['Space48_PreSell::Grouped']['instance']);
    }

    /**
     * @return array[]
     */
    private function getGroupedPluginInfo()
    {
        /** @var PluginList $pluginList */
        $pluginList = $this->objectManager->create(PluginList::class);

        return $pluginList->get(OriginalGrouped::class, []);
    }
}
