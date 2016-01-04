<?php

namespace Examples\Actions\Checkout;

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