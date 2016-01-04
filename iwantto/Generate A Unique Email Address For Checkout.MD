# I Want To Generate A Unique Email Address For Checkout

This is presuming that you are using the guest checkout, though it works equally well when registering during checkout.

Because the checkout uses the customer identity all we need to do is give the customer identity a different email address.  Magium already provides a mechanism for doing that.

```
<?php

namespace Tests\Magium\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\RegisterNewCustomerCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;

class RegisterNewCustomerCheckoutTest extends AbstractMagentoTestCase
{

    public function testBasicCheckout()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $addToCart = $this->getAction(AddItemToCart::ACTION);
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
         $customerCheckout= $this->getAction(RegisterNewCustomerCheckout::ACTION);
        /* @var $customerCheckout \Magium\Magento\Actions\Checkout\RegisterNewCustomerCheckout */

        $customerCheckout->execute();

        self::assertNotEquals('test@example.com', $this->getIdentity()->getEmailAddress());
    }

}

```

The key to this test is the last line where we get the email address and assert that it is not "test@example.com", which is the default.  We can then use this identity to log in, out, and whatnot.