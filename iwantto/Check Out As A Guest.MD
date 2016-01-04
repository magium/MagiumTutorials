# I Want To Check Out As Guest

The guest checkout is going to likely be the most typical means of checking out.  There will be no conflicts between users using this method. And so it should be generally preferred.  To say it another way, try to prefer the guest checkout unless you have a compelling reason to choose a different method.

The guest checkout is built into MagiumMagento and should work out of the box with almost all checkout scenarios provided

1) you are using the Magento OPC Checkout
2) you have not significantly changed the checkout - most look-and-feel changes should not affect the checkout

To execute the checkout process you will need to call `execute()` on the guest checkout object.  Here is a quick example (sans adding a product to the cart).

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;

class GuestCheckoutTest extends AbstractMagentoTestCase
{

    public function testBasicCheckout()
    {
        // imagine code adding a product to the cart

        $this->setPaymentMethod('CashOnDelivery'); // You MUST specify the payment type
        $guestCheckout = $this->getAction(GuestCheckout::ACTION);
        $guestCheckout->execute();

        $orderId = $this->getExtractor(OrderId::EXTRACTOR);
        self::assertNotNull($orderId->getOrderId());
    }

}
```

The checkout can be customized quite heavily, but we won't cover that here.
