<?php
/**
 * GroupedTest.php
 *
 * @Date        07/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

namespace Space48\PreSell\Plugin\GroupedProduct\Model\Product\Type;

use Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection;
use Magento\GroupedProduct\Model\Product\Type\Grouped;
use \Space48\PreSell\Plugin\GroupedProduct\Model\Product\Type\Grouped as GroupedPlugin;

class GroupedTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GroupedPlugin
     */
    private $groupedPlugin;

    /**
     * @var Grouped | \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockProductTypeGrouped;

    /**
     * @var Collection | \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockLinkProductCollection;

    public function setUp()
    {
        $this->groupedPlugin = new GroupedPlugin();

        $this->mockProductTypeGrouped = $this->getMockBuilder(Grouped::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockLinkProductCollection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testTheAfterGetAssociatedProductCollectionCanBeCalled()
    {
        $result = $this->callAfterGetAssociatedProductCollectionMethod();

        $this->assertSame($this->mockLinkProductCollection, $result);
    }

    /**
     * @param Grouped    $subject
     * @param Collection $result
     *
     * @return Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    public function __invoke(Grouped $subject, Collection $result)
    {
//        return $this->mockLinkProductCollection;
    }

    /**
     * @return Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    private function callAfterGetAssociatedProductCollectionMethod()
    {
        $subject = $this->mockProductTypeGrouped;
        $result = $this->mockLinkProductCollection;

        return $this->groupedPlugin->afterGetAssociatedProductCollection($subject, $result);
    }
}
