<?php

namespace Space48\PreSell\Restrictions;

use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class OverSell
{
    protected $quote;
    /**
     * @var QuoteItem\
     */
    protected $quoteItem;
    /**
     * @var \Magento\CatalogInventory\Model\Stock\Item
     */
    protected $stockItem;
    /**
     * @var StockRegistryInterface
     */
    protected $stockRegistry;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    protected $redirect = false;

    public function __construct(
        StockRegistryInterface $stockRegistry,
        ManagerInterface $messageManager
    ) {

        $this->stockRegistry = $stockRegistry;
        $this->messageManager = $messageManager;
    }

    private function sendGlobalMessage($message, $params)
    {
        // Clear messages
        $this->messageManager->getMessages(true);
        $this->messageManager->addWarningMessage(
            __($message,
                $params
            ));
    }

    public function shouldRedirect()
    {
        return $this->redirect;
    }

    public function validate($quoteItem, $redirectOnUpdate = false)
    {
        $this->quoteItem = $quoteItem;
        $this->quote = $quoteItem->getQuote();
        $this->stockItem = $this->stockRegistry->getStockItem(
            $quoteItem->getProduct()->getId(),
            $quoteItem->getProduct()->getStore()->getWebsiteId()
        );

        $qtyInBasket = $this->quoteItem->getQty();
        $qtyInStock = (int) $this->stockItem->getData('qty');
        $qtyAvailableToPreSell = (int) $this->quoteItem->getProduct()->getData('pre_sell_qty');
        $maxAllowedQty = $this->getMaxAllowedQty($qtyInStock, $qtyAvailableToPreSell);

        if ($this->isMoreInBasketThanAvailableAndSomeAreAvailable($qtyInBasket, $maxAllowedQty)){

            $this->quoteItem->clearMessage();

            if ($this->isPreSellProduct() && $qtyAvailableToPreSell > 0) {
                $message = __('We don\'t have as many "%1" as you requested, but we\'ll back order the remaining %2.',
                    $this->stockItem->getProductName(),
                    $qtyAvailableToPreSell
                );
                $this->quoteItem->setData('backorders', $qtyAvailableToPreSell);
                $this->quoteItem->setMessage($message);
            }

            $this->quoteItem->setData('qty', $maxAllowedQty);
            $this->quoteItem->save();

            $this->quote
                ->setTotalsCollectedFlag(false)
                ->collectTotals()
                ->save();

            $this->sendGlobalMessage(
                'Sorry we don\'t have %1 available, the quantity has been reduced to %2',
                [
                    $qtyInBasket,
                    $maxAllowedQty
                ]
            );

            if ($redirectOnUpdate) {
                $this->redirect = true;
            }
        }

        if ($maxAllowedQty == 0) {
            $message = __('Sorry, this item is now out of stock. Please remove it from your basket to checkout',
                $this->stockItem->getProductName(),
                $qtyAvailableToPreSell
            );
            $this->quoteItem->clearMessage();
            $this->quoteItem->setMessage($message);

            $this->redirect = true;

        }
    }

    protected function isPreSellProduct()
    {
        return $this->quoteItem->getProduct()->getData('pre_sell');
    }

    protected function getMaxAllowedQty($qtyInStock, $qtyAvailableToPreSell)
    {
        if ($this->isPreSellProduct()) {
            return $qtyInStock + $qtyAvailableToPreSell;
        } else {
            return $qtyInStock;
        }
    }

    protected function isMoreInBasketThanAvailableAndSomeAreAvailable($qtyInBasket, $maxAllowedQty)
    {
        return $qtyInBasket > $maxAllowedQty && $maxAllowedQty > 0;
    }
}
