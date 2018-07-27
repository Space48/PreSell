<?php

namespace Space48\PreSell\Observer\Restrictions;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class Product
{
    public static function isExcludedState(QuoteItem $productItem): bool
    {
        return !$productItem ||
            !$productItem->getProductId() ||
            !$productItem->getQuote() ||
            $productItem->getProductType() === Configurable::TYPE_CODE;
    }
}
