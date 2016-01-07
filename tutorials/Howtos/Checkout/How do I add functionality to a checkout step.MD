How do I add functionality to a checkout step?
==============================================

In the course of days you might need to add some level of customized functionality to a checkout step.  For example, let's consider the following user story.

`As a customer who has a poor credit rating they should not see the ABC Payment Co. method`

The context of this could be that there is a feature that allows you to identify individual customers as credit risks and so they should not even see ABC Payment Co. payment method.

To do this requires you to override the existing `PaymentMethod` step class.

```
namespace Test;

class BadCreditPaymentMethod extends \Magium\Magento\Actions\Checkout\Steps\PaymentMethod
{
    public function execute()
    {
        $this->testCase->assertElementNotExists('payment_method_abc_payment_co');
        return parent::execute();
    }
}
```
[(source)](../../../howto/Checkout/BadCreditShowsNoABCPaymentTest.php)

This class re-uses the functionality in the original `PaymentMethod` class but makes an assertion that the ABC Payment Co.'s payment method is not there.

Then when we write our test we need to use Magium's internal dependency injection container to set our new class as a type preference for the `PaymentMethod` class.

```
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

```

One thing to note here about the checkout steps.  Each step implements `Magium\Magento\Actions\Checkout\Steps\StepInterface`.  The source for this interface is

```
<?php
namespace Magium\Magento\Actions\Checkout\Steps;

interface StepInterface
{
    public function execute();

    public function nextAction();
}
```

The `execute()` method is intended to contain the form-entering functionality (such as entering the customer information).  `nextAction()` is intended to be called when the "Continue" button is clicked.  These two actions are separated precisely so you can make pre and post `execute()` changes to the class without worrying about the "Continue" button being clicked.