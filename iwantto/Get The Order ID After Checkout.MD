# I Want To Get The Order ID After Checkout

Oftentimes the Order ID is needed either to validate that an order occurred or for some future navigation, such as when you need to look it up from the customer page or the admin UI.

Getting it is easy.  In fact, if you run through a successful checkout its already there.  Extracting it is one of [steps of the checkout](https://github.com/magium/MagiumMagento/blob/master/lib/Magento/Actions/Checkout/GuestCheckout.php) (coupled with an extractor that [understands checkout](https://github.com/magium/MagiumMagento/blob/master/lib/Magento/Extractors/Checkout/OrderId.php) (check out the `StepInterface` in the declaration)).  The OrderID is processed immediately at the end of the checkout process so you don't need to worry about getting it before moving to another page.

So, really, all you need to do to get the extractor and call `getOrderId()`.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;

class NavigateToOrderTest extends AbstractMagentoTestCase
{

    public function testAdminNavigationToOrderSucceeds()
    {
        // Imagine a product added to the cart
        $this->setPaymentMethod('CashOnDelivery');
        $guestCheckout = $this->getAction(GuestCheckout::ACTION);
        /* @var $guestCheckout \Magium\Magento\Actions\Checkout\GuestCheckout */

        $guestCheckout->execute();

        $orderId = $this->getExtractor(OrderId::EXTRACTOR)->getOrderId();
    }

}

```