# I Want To Get The Totals From The Place Order Screen

You might want to get the totals from the order screen.  There are a few possible reasons for this.  You might want to

* Validate the currency symbol
* Validate the tax amount
* Validate that a shipping method was chosen

The summary extractor will extract the products, subtotal, tax, and other things immediately prior to clicking "Place Order".  The extractor is added as part of the checkout process and so you can get the order summary information (including totals) immediately after the checkout has been completed.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Extractors\Checkout\CartSummary;

class CartSummaryTest extends AbstractMagentoTestCase
{

    public function testGuestCheckout()
    {
        // Imagine adding one product to the cart

        $this->setPaymentMethod('CashOnDelivery');
        $guestCheckout = $this->getAction(GuestCheckout::ACTION);
        /* @var $guestCheckout \Magium\Magento\Actions\Checkout\GuestCheckout */

        $guestCheckout->execute();

        $cartSummary = $this->getExtractor(CartSummary::EXTRACTOR);
        /* @var $cartSummary \Magium\Magento\Extractors\Checkout\CartSummary */
        self::assertNotNull($cartSummary->getGrandTotal());
        self::assertCount(1, $cartSummary->getProducts());
    }

}
```
See the [CartSummary Extractor](https://github.com/magium/MagiumMagento/blob/master/lib/Magento/Extractors/Checkout/CartSummary.php) for more information.

If you would like to get the totals without placing the order you would need to modify the checkout slightly by adding a `StopProcessing` step prior to the `PlaceOrder` step.

```
<?php


use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Actions\Checkout\Steps\PlaceOrder;
use Magium\Magento\Actions\Checkout\Steps\StopProcessing;
use Magium\Magento\Extractors\Checkout\CartSummary;
use Magium\Magento\Extractors\Checkout\OrderId;

class StopOrderTest extends AbstractMagentoTestCase
{

    public function testCheckoutIsStopped()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(AddItemToCart::ACTION)->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
        $checkout = $this->getAction(GuestCheckout::ACTION);
        /* @var $checkout \Magium\Magento\Actions\Checkout\GuestCheckout */

        // Add the StopOrder step before the PlaceOrder step
        $checkout->addStep(
            $this->getAction(StopProcessing::ACTION),
            $this->getAction(PlaceOrder::ACTION)
        );

        $checkout->execute();

        self::assertNull($this->getExtractor(OrderId::EXTRACTOR)->getOrderId());
        self::assertNotNull($this->getExtractor(CartSummary::EXTRACTOR)->getGrandTotal());
    }

}
```
([Source](../examples/Actions/Checkout/StopOrderTest.php))
