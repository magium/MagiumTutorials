# I Want To Configure A Payment Module

There are a few steps involved in configuring a payment method.

* Navigate to the admin page
* Log in to the admin UI
* Navigate to System/Configuration
* Use the enabler to enable the payment method (changing individual settings will be covered later on)

An enabler is very simple to use.  It simply finds the appropriate tab/section and finds a label called "Enable".  It will then enable or disable the module by setting its "Enable" method to 0 or 1.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Configuration\Enabler;
use Magium\Magento\Actions\Admin\Login\Login;
use Magium\Magento\Navigators\Admin\AdminMenu;

class EnablePaymentMethodTest extends AbstractMagentoTestCase
{

    public function testEnablePaymentMethod()
    {
        $this->commandOpen($this->getTheme('Admin\ThemeConfiguration')->getBaseUrl());
        $this->getAction(Login::ACTION)->login();
        $this->getNavigator(AdminMenu::NAVIGATOR)->navigateTo('System/Configuration');
        $this->getAction(Enabler::ACTION)->enable('Payment Methods/My Payment Method');
    }

}
```

Changing payment settings is more difficult and should be considered a work in progress as more payment methods are going to be added.  In other words, not all payment methods will be supported using this method, but new payment methods will be provided as time goes on.

There is a lot of underlying abstraction here. [Get some background if you are not familiar with it](Make Configuration Changes.MD).

## Payment Setting Implementations

Payment settings classes should extend `Magium\Magento\Actions\Admin\Configuration\PaymentMethods\AbstractPaymentMethod`, which, in turn, extends `Magium\Magento\Actions\Admin\Configuration\AbstractSettingGroup`.  This is because payment methods will often have similar bits of functionality or setting titles.  For example, "Pending" is a standard option and so it is provided via a constant so you don't accidentally fat-finger it.

Because this class extends the `AbstractSettingGroup` it will automatically click the save button when you call `execute()`.

The net result of this is that, for defined payment setting classes, you should not have to even think about touching Xpath... unless you are trying to navigate the Paypal shambles (respect to whomever had to build that out from the specs).