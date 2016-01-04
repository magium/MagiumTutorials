# I Want To Go To An Order In The Admin

Once an order is placed on the frontend you might want to go to it in the backend to get additional order information to extract, to test invoicing/shipping or to test cancellation, among about a billion other things.

But all you really need to know is the OrderID, which you can get [after an order has been placed](Get The Order ID After Checkout.MD).

```
<?php

namespace Tests\Magium\Magento\Admin\Order;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Login\Login;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;
use Magium\Magento\Navigators\Admin\Order;

class OrderInformationExtractorTest extends AbstractMagentoTestCase
{


    public function testOrderExtraction()
    {

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $addToCart = $this->getAction(AddItemToCart::ACTION);
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');

        $guestCheckout = $this->getAction(GuestCheckout::ACTION);
        $guestCheckout->execute();

        // 1) Get the Order ID
        $orderId = $this->getExtractor(OrderId::EXTRACTOR)->getOrderId();

        // 2) Log in to the admin UI
        $this->getAction(Login::ACTION)->login();

        // 3) Navigate
        $this->getNavigator(Order::NAVIGATOR)->navigateTo($orderId);
    }

}
```

Once logged in the navigator will navigate to Order/Sales, search for the correct order ID, and click on the order, leaving you at the order page.

Of course at that point you can run several extractors on that page.  You can see all of the extractors in the [Order Information Extractor](https://github.com/magium/MagiumMagento/blob/master/tests/Magento/Admin/Order/OrderInformationExtractorTest.php) test.