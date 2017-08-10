<?php

namespace Space48\PreSell\Plugin\Checkout\Model;

class GuestPaymentInformationManagement
{
    public function aroundSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Model\GuestPaymentInformationManagement $subject,
        callable $proceed,
        ...$args
    ) {
        try {
            $result = $proceed(...$args);
        } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
            if ($e->getPrevious()->getMessage() == "Order could not be placed as the stock is not available.") {
                throw $e->getPrevious();
            }
            else {
                throw $e;
            }

        }

        return $result;
    }
}
