# I Want To Check Out As A Registered User

If you need to checkout as a registered users there are two things you need to be aware of:

1) The registered user checkout object
2) The customer identity

The registered user checkout object works verily similar to the [Guess Checkout class](Check Out As A Guest.MD).  The primary difference is that it will use a [default customer](Configure A Default Customer.MD) to log in during the checkout process.  Using the registered checkout does not require you to reference the customer identity in your test if you intend to use the defaults.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\CustomerCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;

class CustomerCheckoutTest extends AbstractMagentoTestCase
{

    public function testBasicCheckout()
    {
           // Imagine a product added to the cart

        $this->setPaymentMethod('CashOnDelivery');
         $customerCheckout= $this->getAction(CustomerCheckout::ACTION);
        $customerCheckout->execute();

        $orderId = $this->getExtractor(OrderId::class);
        /** @var $orderId OrderId */
        self::assertNotNull($orderId->getOrderId());
    }

}
```

Say you want to change the user that you are going to log in as.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\CustomerCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;

class CustomerCheckoutTest extends AbstractMagentoTestCase
{

    public function testBasicCheckout()
    {
           // Imagine a product added to the cart

        $this->getIdentity()->setEmailAddress('myaddress@example.com');
        $this->getIdentity()->setPassword('password');

        $this->setPaymentMethod('CashOnDelivery');
         $customerCheckout= $this->getAction(CustomerCheckout::ACTION);
        $customerCheckout->execute();

        $orderId = $this->getExtractor(OrderId::class);
        /** @var $orderId OrderId */
        self::assertNotNull($orderId->getOrderId());
    }

}
```

