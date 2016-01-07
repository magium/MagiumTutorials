<?php

namespace Test;

use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\CustomerCheckout;

class BadCreditShowsNoABCPaymentTest extends \Magium\Magento\AbstractMagentoTestCase
{

    public function testBadCreditShowsNoABCPaymentMethodTest()
    {
        $this->setPaymentMethod('CashOnDelivery'); // Sets the actual payment method
        $this->setTypePreference(
            'Magium\Magento\Actions\Checkout\Steps\PaymentMethod',
            'Test\BadCreditPaymentMethod'
        );

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(AddItemToCart::ACTION)->addSimpleProductToCartFromCategoryPage();
        $this->getAction(CustomerCheckout::ACTION)->execute();
    }

}

class BadCreditPaymentMethod extends \Magium\Magento\Actions\Checkout\Steps\PaymentMethod
{
    public function execute()
    {
        $this->testCase->assertElementNotExists('payment_method_abc_payment_co');
        return parent::execute();
    }
}