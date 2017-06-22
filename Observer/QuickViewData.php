<?php

namespace Space48\PreSell\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Event\Observer;
use Space48\PreSell\Model\PreSell;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Url\Helper\Data as FrameworkHelper;
use Magento\Catalog\Model\Session;

class QuickViewData implements ObserverInterface
{
    private $jsonHelper;
    private $preSell;
    private $urlBuilder;
    private $frameworkHelper;
    private $catalogSession;

    public function __construct(
        JsonHelper $jsonHelper,
        PreSell $preSell,
        UrlInterface $urlBuilder,
        FrameworkHelper $frameworkHelper,
        Session $catalogSession
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->preSell = $preSell;
        $this->urlBuilder = $urlBuilder;
        $this->frameworkHelper = $frameworkHelper;
        $this->catalogSession = $catalogSession;
    }

    public function execute(Observer $observer)
    {
        $specialQuickViewData = $this->catalogSession->getSpecialQuickViewData();
        $product = $observer->getEvent()->getProduct();
        $encodedUrl = $this->getSignUpUrl($product);

        $newSpecialData = [
            'show_add_to_cart'      => $this->showAddToCartButtonAfterPreSellCheck($product),
            'show_notify_button'    => $this->showNotifyButton($product),
            'notify_url'            => $encodedUrl,
            'uenc'                  => $this->getUencFromUrl($encodedUrl)
        ];

        $this->catalogSession->setSpecialQuickViewData(array_merge($newSpecialData, $specialQuickViewData));
    }

    /**
     * @param $url
     * @return string
     */
    private function getUencFromUrl($url)
    {
        $parts = parse_url($url);
        $urlArray = explode("/", $parts['path']);
        $uencKey = array_search("uenc", $urlArray);
        $uenc = $urlArray[$uencKey+1];

        return $uenc ? $uenc : "";
    }

    /**
     * @param $product
     * @return bool
     */
    private function showNotifyButton($product)
    {
        return $this->preSell->isProductStockLevelAboveZero($product)
            ? false : true;
    }

    /**
     * @param $product
     * @return bool
     */
    private function showAddToCartButtonAfterPreSellCheck($product)
    {
        return $this->preSell->isProductInStockOrAvailableForPreSale($product);
    }

    /**
     * @param string $product
     * @return string
     */
    private function getSignUpUrl($product)
    {
        return $this->urlBuilder->getUrl(
            'productalert/add/stock',
            [
                'product_id' => $product->getId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->frameworkHelper->getEncodedUrl()
            ]
        );
    }
}
