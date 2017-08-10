<?php

namespace Space48\PreSell\Plugin\GroupedProduct\Model\Product\Type;

use \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection;
use \Magento\GroupedProduct\Model\Product\Type\Grouped as TypeGrouped;

class GroupedPlugin
{

    /**
     * @param TypeGrouped $subject
     * @param Collection  $result
     *
     * @return Collection
     */
    public function afterGetAssociatedProductCollection(TypeGrouped $subject, Collection $result)
    {
        $result->addAttributeToSelect('pre_sell_qty');
        $result->addAttributeToSelect('pre_sell');
        $result->addAttributeToSelect('available_from_x');

        return $result;
    }
}
