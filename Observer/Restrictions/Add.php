<?php

namespace Space48\PreSell\Observer\Restrictions;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;


class Add implements ObserverInterface
{
    /**
     * @var \Space48\PreSell\Restrictions\OverSell
     */
    protected $overSell;
    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    protected $responseFactory;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $httpRequest;

    public function __construct(
        \Space48\PreSell\Restrictions\OverSell $overSell,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Request\Http $httpRequest
    )
    {
        $this->overSell = $overSell;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->httpRequest = $httpRequest;
    }


    public function execute(Observer $observer)
    {
        /* @var $quoteItem \Magento\Quote\Model\Quote\Item */
        $quoteItem = $observer->getEvent()->getQuoteItem();

        if (!$quoteItem ||
            !$quoteItem->getProductId() ||
            !$quoteItem->getQuote() ||
            $quoteItem->getQuote()->getIsSuperMode() ||
            $quoteItem->getProductType() === Configurable::TYPE_CODE
        ) {
            return;
        }

        $this->overSell->validate($quoteItem);

        if ($this->overSell->shouldRedirect()) {
            if (! $this->httpRequest->isAjax()) {
                if ($this->httpRequest->getPathInfo() !== '/checkout/cart/' &&
                    $this->httpRequest->getPathInfo() !== '/checkout/cart/index/') {
                    $this->responseFactory->create()->setRedirect($this->url->getUrl('checkout/cart/index'))->sendResponse();
                    exit;
                }
            }
        }
    }
}
