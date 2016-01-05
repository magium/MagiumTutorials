# I Want To Set A Billing Address During Checkout For A Guest Customer

Setting the billing address for a guest customer is easy because you *always* have to set a billing address.  A [registered user checkout](Set A Billing Address During Checkout For A Registered Customer.MD) is a little more difficult because there may be existing addresses in the drop down menu.

```
<?php

namespace Tests\Magium\Magento\Extractors;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\CustomerCheckout;

class CustomerOrderExtractorTest extends AbstractMagentoTestCase
{
    public function testOrderExtractor()
    {
        // Changing the billing address for the customer
        $customer = $this->getIdentity();
        $customer->setBillingStreet1('123 My St');

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->setPaymentMethod('CashOnDelivery');
        $this->getAction(AddItemToCart::ACTION)->addSimpleProductToCartFromCategoryPage();
        $this->getAction(CustomerCheckout::ACTION)->execute();
    }

}
```