<?php

namespace Space48\PreSell\Block;

use Magento\CatalogInventory\Model\StockRegistry;

class PreSell
{

    /**
     * @var StockRegistry
     */
    private $stockItem;

    public function __construct(
        StockRegistry $stockItem
    ) {
        $this->stockItem = $stockItem;
    }

    /**
     * If the passed product is not simple, then the item is automatically
     * considered to be in stock and available for preSale. Otherwise we
     * find the combined qty and preSell qty of the simple product and check
     * if it is above zero.
     *
     * @param $product
     *
     * @return bool
     */
    public function isProductInStockOrAvailableForPreSale($product)
    {
        if (!$this->isValidStockProduct($product)) {
            return true;
        }

        if ($this->getTotalQty($product) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return bool
     */
    public function isValidStockProduct($product)
    {
        return $product && $product->getData('type_id') == "simple" ? true : false;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return float
     */
    public function getTotalQty($product)
    {
        $stockQty = $this->getStockQty($product);
        $preSellQty = 0;

        if ($this->canPreSell($product)) {
            $preSellQty = $product->getData('pre_sell_qty');
        }

        return $stockQty + $preSellQty;
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return float
     */
    public function getStockQty($product)
    {
        $productId = $this->getItemId($product);

        return $this->getStockItemQty($productId);
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return mixed
     */
    public function getItemId($product)
    {
        return $product->getData('row_id') ? $product->getData('row_id') : $product->getData('entity_id');
    }

    /**
     * @param $productId
     *
     * @return float
     */
    public function getStockItemQty($productId)
    {
        $stock = $this->stockItem->getStockItem($productId);

        return $stock->getQty();
    }

    /**
     * @param $product \Magento\Catalog\Model\Product
     *
     * @return bool
     */
    public function canPreSell($product)
    {
        if (!$product->getData('pre_sell')) {
            return false;
        }

        return $product->getData('pre_sell_qty') > 0 ? true : false;
    }

    /**
     * If the passed product is not simple, then the stock level of the item
     * is automatically considered above zero. Otherwise we find the stock
     * level of the simple product and check if it is above zero.
     *
     * @param $product
     *
     * @return bool
     */
    public function isProductStockLevelAboveZero($product)
    {
        if (!$this->isValidStockProduct($product)) {
            return true;
        }

        if ($this->getStockQty($product) > 0) {
            return true;
        }

        return false;
    }
}
