# I Want To Validate Or Verify That A Payment Method Is Available In Checkout

This scenario is not too difficult to do, but it will require building a separate class to do the assertion.

```
<?php

namespace Examples\Actions\Checkout;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Actions\Checkout\Steps\PaymentMethod;
use Magium\Magento\Actions\Checkout\Steps\StepInterface;

class ValidatePaymentTypeIsAvailableTest extends AbstractMagentoTestCase
{

    public function testPaymentIsAvailable()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(AddItemToCart::ACTION)->addSimpleProductToCartFromCategoryPage();

        $checkout = $this->getAction(GuestCheckout::ACTION);
        /* @var $checkout \Magium\Magento\Actions\Checkout\GuestCheckout */
        $checkout->addStep(
            $this->get('Examples\Actions\Checkout\ValidateMyPaymentMethod'),
            $this->getAction(PaymentMethod::ACTION)
        );
        $checkout->execute();

        self::assertTrue($this->get('Examples\Actions\Checkout\ValidateMyPaymentMethod')->getExecuted());
    }

}

class ValidateMyPaymentMethod implements StepInterface
{

    protected $testCase;
    protected $executed = false;

    public function __construct(
        AbstractMagentoTestCase $testCase
    )
    {
        $this->testCase = $testCase;
    }

    public function getExecuted()
    {
        return $this->executed;
    }

    public function execute()
    {

        $this->testCase->byText('Cash On Delivery');
        $this->executed = true;
        return false;
    }

    public function nextAction()
    {
        return false;
    }

}
```