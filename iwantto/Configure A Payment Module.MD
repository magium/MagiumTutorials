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

There is a lot of underlying abstraction here so let's set some background.

## Setting Modifiers

A setting modifier is based off of `Magium\Magento\Actions\Admin\Configuration\SettingModifier`.  it is based purely off of the notion that working with the System Configuration sucks.  What it does is take a setting (from you) and set its value.

A setting modifier has two ways the setting can be specified.  1) via its ID (every element in the System Configuration has an ID (this is actually really nice)), 2) via its label.

The syntax for a setting modifier is "Tab/Section::setting".  The setting can be either the ID (default) or a label (by prepending the setting with the text label=).

For for example, the following code snippets do exactly the same thing.

```
$modifier = $this->getAction(SettingModifier::ACTION);

$modifier->set('General/Countries Options::label=Allow Countries', ['CA', 'US']);

$modifier->set('General/Countries Options::general_country_allow', ['CA', 'US']);
```

To make matters even more confusing the tab and section do not need to be provided.  If they are omitted (noted by omitting the :: token) then the setting modifier will try to find the ID/label on the current page.  If the :: is provided it will check to see if the current page matches the tab/section combination and if it does not it will call `Magium\Magento\Navigators\Admin\SystemConfiguration` to navigate to the correct tab.  This complexity is there to (drastically) reduce the amount of wall clock time needed to change a setting.

By default, calling `set()` will not save the item.  This is because the setting modifier is presumed to be part of a setting group.

## Setting Groups

The base setting group class is an abstract class called `Magium\Magento\Actions\Admin\Configuration\AbstractSettingGroup`.  This class is intended to be extended for groups of settings, such as payment methods.  The extending class is expected to define each individual setting.  (See the [Cash On Delivery](https://github.com/magium/MagiumMagento/blob/master/lib/Magento/Actions/Admin/Configuration/PaymentMethods/CashOnDelivery.php) code for more detail)

The setting group method `execute()` will iterate over all of the settings and then click the ave button unless you've explicitly declared that it should not via `disableSave()`.

## Payment Setting Implementations

Payment settings classes should extend `Magium\Magento\Actions\Admin\Configuration\PaymentMethods\AbstractPaymentMethod`, which, in turn, extends `Magium\Magento\Actions\Admin\Configuration\AbstractSettingGroup`.  This is because payment methods will often have similar bits of functionality or setting titles.  For example, "Pending" is a standard option and so it is provided via a constant so you don't accidentally fat-finger it.

Because this class extends the `AbstractSettingGroup` it will automatically click the save button when you call `execute()`.

The net result of this is that, for defined payment setting classes, you should not have to even think about touching Xpath... unless you are trying to navigate the Paypal shambles (respect to whomever had to build that out from the specs).