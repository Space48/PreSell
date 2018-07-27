<?php

namespace Space48\PreSell\Observer\Restrictions;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class Update implements ObserverInterface
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
        $quote = $observer->getEvent()->getCart()->getQuote();

        $redirect = false;

        foreach ($quote->getAllItems() as $quoteItem) {
            if (!$quoteItem ||
                !$quoteItem->getProductId() ||
                !$quoteItem->getQuote() ||
                $quoteItem->getQuote()->getIsSuperMode() ||
                $quoteItem->getProductType() === Configurable::TYPE_CODE
            ) {
                return;
            }

            $this->overSell->validate($quoteItem);

            if (! $redirect) {
                $redirect = $this->overSell->shouldRedirect();
            }
        }

        if ($redirect) {
            if ($this->httpRequest->getControllerName() !== 'cart') {
                if (! $this->httpRequest->isAjax()) {
                    $this->responseFactory->create()->setRedirect($this->url->getUrl('checkout/cart/index'))->sendResponse();
                } else {
                    throw new \Magento\Framework\Exception\LocalizedException(__("Order could not be placed as the stock is not available."));
                }
            }
        }
    }
}
