<?php

namespace Space48\PreSell\Observer\Restrictions;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Space48\PreSell\Observer\Restrictions\Product;

class Order implements ObserverInterface
{
    /**
     * @var \Space48\PreSell\Restrictions\OverSell
     */
    protected $overSell;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;
    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $responseFactory;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $url;
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $httpRequest;

    public function __construct(
        \Space48\PreSell\Restrictions\OverSell $overSell,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Request\Http $httpRequest

    )
    {
        $this->overSell = $overSell;
        $this->quoteRepository = $quoteRepository;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->httpRequest = $httpRequest;
    }


    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $redirect = false;

        foreach ($quote->getAllItems() as $quoteItem) {
            if (Product::isExcludedState($quoteItem)) {
                return;
            }

            $this->overSell->validate($quoteItem, true);

            if (! $redirect) {
                $redirect = $this->overSell->shouldRedirect();
            }
        }

        if ($redirect) {
            if (! $this->httpRequest->isAjax()) {
                $this->responseFactory->create()->setRedirect($this->url->getUrl('checkout/cart/index'))->sendResponse();
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(__("Order could not be placed as the stock is not available."));
            }
        }
    }
}
