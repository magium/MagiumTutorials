# I Want To Register During Checkout

Registering checkout requires

* A customer identity
* The Register-During-Checkout action

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\RegisterNewCustomerCheckout;

class RegisterNewCustomerCheckoutTest extends AbstractMagentoTestCase
{

    public function testBasicCheckout()
    {
        // Imagine adding a product to the cart
        $this->setPaymentMethod('CashOnDelivery');
         $customerCheckout= $this->getAction(RegisterNewCustomerCheckout::ACTION);
        /* @var $customerCheckout \Magium\Magento\Actions\Checkout\RegisterNewCustomerCheckout */

        $customerCheckout->execute();

        self::assertNotEquals('test@example.com', $this->getIdentity()->getEmailAddress());
    }

}
```

This example requires some explanation.  *Where was the email address specified?*  Answer:  it wasn't.  `The RegisterNewCustomerCheckout` class will ask the identity to auto-generate an email address unless you provide one.  But if you do provide one you have to tell the customer identity not to create a new one.

```
<?php

namespace Tests\Magium\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\RegisterNewCustomerCheckout;

class RegisterNewCustomerCheckoutTest extends AbstractMagentoTestCase
{


    public function testCheckoutWithSpecifiedEmailAddress()
    {
        $customer = $this->getIdentity();
        /* @var $customer \Magium\Magento\Identities\Customer */

        $address = hash_hmac('sha256', uniqid(), '') . '@example.com';
        $customer->setEmailAddress($address);
        $customer->setUniqueEmailAddressGenerated(true); // requried

        // Imagine adding a product to the cart

        $this->setPaymentMethod('CashOnDelivery');
        $customerCheckout= $this->getAction(RegisterNewCustomerCheckout::ACTION);
        /* @var $customerCheckout \Magium\Magento\Actions\Checkout\RegisterNewCustomerCheckout */

        $customerCheckout->execute();

        self::assertEquals($address, $this->getIdentity()->getEmailAddress());
    }

}

```