# I Want To Set A Billing Address During Checkout For A Registered Customer

This problem is a little more difficult than a guest checkout because there may already be an address saved for the account in a dropdown.  So you then have to click the "Add New Address" dropdown before entering the billing information.

Magium has a simple means of doing this, but you have to dig into the checkout to get the `BillingAddress` class to set the `enterNewAddress()` method.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\CustomerCheckout;
use Magium\Magento\Actions\Checkout\Steps\CustomerBillingAddress;

class CustomerCheckoutTest extends AbstractMagentoTestCase
{

    public function testCheckoutwithDifferentBillingAddress()
    {

        // Imagine adding something to the cart

        $this->setPaymentMethod('CashOnDelivery');
        $customerCheckout = $this->getAction(CustomerCheckout::ACTION);
        /* @var $customerCheckout \Magium\Magento\Actions\Checkout\CustomerCheckout */

        $customerBilling = $this->getAction(CustomerBillingAddress::ACTION);
        /* @var $customerBilling \Magium\Magento\Actions\Checkout\Steps\CustomerBillingAddress */

        $customerBilling->enterNewAddress();
        $this->getIdentity()->setBillingFirstName('Bob');

        $customerCheckout->execute();
    }

}
```