# I Want To Validate Or Verify That The Address I Used During Checkout Is On The Order

To validate that an order has properly take the correct address you will need to know a combination of the customer identity, the billing address step, and the order summary extractor (not to mention the whole place-order workflow).

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\CustomerCheckout;
use Magium\Magento\Actions\Checkout\Steps\CustomerBillingAddress;
use Magium\Magento\Extractors\Checkout\OrderId;
use Magium\Magento\Extractors\Customer\Order\BillingAddress;
use Magium\Magento\Navigators\Customer\AccountHome;
use Magium\Magento\Navigators\Customer\NavigateToOrder;

class UseDifferentCustomerAddressDuringCheckoutTest extends AbstractMagentoTestCase
{

    public function testUseDifferentAddress()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $identity = $this->getIdentity();
        /* @var $identity \Magium\Magento\Identities\Customer */

        // Set the new street value
        $identity->setBillingAddress('098 my st');

        $this->getAction(AddItemToCart::ACTION)->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
        $billingAddress = $this->getAction(CustomerBillingAddress::ACTION);
        /* @var $billingAddress CustomerBillingAddress */

        /*
         * Because we are logging in as a registered customer we may need to
         * tell Magium that we don't want to use a saved address during checkout
         */
        $billingAddress->enterNewAddress();
        $this->getAction(CustomerCheckout::ACTION)->execute();

        $this->getNavigator(AccountHome::NAVIGATOR)->navigateTo();
        $this->getNavigator(NavigateToOrder::NAVIGATOR)->navigateTo($this->getExtractor(OrderId::EXTRACTOR)->getOrderId());

        $extractor = $this->getExtractor(BillingAddress::EXTRACTOR);
        /* @var $extractor \Magium\Magento\Extractors\Customer\Order\BillingAddress */
        $extractor->extract();
        self::assertEquals('098 my st', $extractor->getStreet1());

    }

}
```