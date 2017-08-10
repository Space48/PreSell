/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'mage/url',
        'Magento_Ui/js/model/messageList'
    ],
    function (url, globalMessageList) {
        'use strict';

        return {
            process: function (response, messageContainer) {
                messageContainer = messageContainer || globalMessageList;
                if (response.status == 401) {
                    window.location.replace(url.build('customer/account/login/'));
                } else {
                    var error = JSON.parse(response.responseText);
                    if (error.message == "Order could not be placed as the stock is not available.") {
                        window.location.replace(url.build('checkout/cart/'));
                    }
                    messageContainer.addErrorMessage(error);
                }
            }
        };
    }
);
