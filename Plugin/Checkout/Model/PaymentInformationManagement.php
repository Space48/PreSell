<?php

namespace Space48\PreSell\Plugin\Checkout\Model;

class PaymentInformationManagement
{
    public function aroundSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        callable $proceed,
        ...$args
    ) {
        try {
            $result = $proceed(...$args);
        } catch (\Magento\Framework\Exception\CouldNotSaveException $e) {
            throw $e->getPrevious();
        }

        return $result;
    }
}
