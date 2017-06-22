<?php

namespace Space48\PreSell\Plugin\ProductAlert\Controller\Add;

class Stock
{
    public function aroundExecute(\Magento\ProductAlert\Controller\Add\Stock $subject, callable $proceed)
    {
        $referrerUrl = $subject->getRequest()->getServer('HTTP_REFERER');
        $returnValue = $proceed();
        $returnValue->setUrl($referrerUrl);

        return $returnValue;
    }

}
