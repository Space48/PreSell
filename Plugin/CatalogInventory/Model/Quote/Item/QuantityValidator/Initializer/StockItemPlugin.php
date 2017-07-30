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

use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Model\Quote\Item\QuantityValidator\Initializer\StockItem as StockItemInitializer;
use Magento\CatalogInventory\Model\Stock\Item as StockItem;
use Magento\CatalogInventory\Model\StockStateProvider;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class StockItemPlugin
{

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * StockStateProvider constructor.
     *
     * @param ManagerInterface $messageManager
     *
     */
    public function __construct(
        ManagerInterface $messageManager
    ) {
        $this->messageManager = $messageManager;
    }

    /**
     * @param StockStateProvider $subject
     * @param callable           $proceed
     * @param StockItem          $stockItem
     * @param QuoteItem          $quoteItem
     * @param int                $qty
     *
     * @return \Magento\Framework\DataObject
     *
     */
    public function aroundInitialize( StockItemInitializer $subject,
        callable $proceed,
        StockItem $stockItem,
        QuoteItem $quoteItem,
        $qty)
    {
        /** @var \Magento\Framework\DataObject $result */
        $result = $proceed($stockItem, $quoteItem, $qty);

        if ($stockItem->getBackorders() && $this->canPreSell($quoteItem)) {
            if ($this->getMaxAllowedQty($stockItem, $quoteItem) - $result->getData('orig_qty') < 0) {

                $quoteItem->setData('qty', $this->getMaxAllowedQty($stockItem, $quoteItem));

                $this->messageManager->addNoticeMessage(
                    __('Quantity was recalculated from %1 to %2', $result->getData('orig_qty'), $this->getMaxAllowedQty($stockItem, $quoteItem)));

            }
        }

        return $result;
    }

    /**
     * @param StockItem $stockItem
     * @param QuoteItem $quoteItem
     *
     * @return mixed
     */
    private function getMaxAllowedQty($stockItem, $quoteItem)
    {
        return $stockItem->getData('qty') + $this->getPreSellQty($quoteItem);
    }

    /**
     * @param $quoteItem
     *
     * @return int
     */
    private function getPreSellQty($quoteItem)
    {
        return $this->getProduct($quoteItem)->getData('pre_sell_qty');
    }

    /**
     * @param QuoteItem $quoteItem
     *
     * @return Product
     */
    private function getProduct($quoteItem)
    {
        return $quoteItem->getData('product');
    }

    /**
     * @param $quoteItem
     *
     * @return bool
     */
    private function canPreSell($quoteItem)
    {
        return $this->getProduct($quoteItem)->getData('pre_sell') ? true : false;
    }
}
