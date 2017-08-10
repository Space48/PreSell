define(['Magento_Customer/js/customer-data'], function (customerData) {
    var mageJsComponent = function () {
        customerData.reload(['cart'], false);
    };
    return mageJsComponent;
});
