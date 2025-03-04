<?php

namespace Space48\PreSell\Plugin\Model\Product\Type;

use \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection;
use \Magento\GroupedProduct\Model\Product\Type\Grouped as TypeGrouped;


class GroupedPlugin
{
    public function afterGetAssociatedProductCollection(TypeGrouped $subject, Collection $result)
    {
        $result->addAttributeToSelect('pre_sell_qty');
        $result->addAttributeToSelect('pre_sell');
        $result->addAttributeToSelect('available_from_x');

        return $result;
    }
}
