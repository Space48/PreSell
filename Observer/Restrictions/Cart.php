<?php

namespace Space48\PreSell\Observer\Restrictions;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Space48\PreSell\Observer\Restrictions\Product;

class Cart implements ObserverInterface
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
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $session;

    public function __construct(
        \Space48\PreSell\Restrictions\OverSell $overSell,
        \Magento\Checkout\Model\Session $session,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Request\Http $httpRequest

    )
    {
        $this->overSell = $overSell;
        $this->session = $session;
        $this->responseFactory = $responseFactory;
        $this->url = $url;
        $this->httpRequest = $httpRequest;
    }


    public function execute(Observer $observer)
    {
        $quote = $this->session->getQuote();

        foreach ($quote->getAllItems() as $quoteItem) {
            if (Product::isExcludedState($quoteItem)) {
                return;
            }

            $this->overSell->validate($quoteItem);
        }
    }
}
