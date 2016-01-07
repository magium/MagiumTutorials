# I Want To Make Configuration Changes

Automating configuration changes is really hard to do in Magento.  Well, not *hard*, but time consuming, tedious, and generally the cause of much hair loss.

To make that easier there are two significant items that you will need to understand

## Setting Modifiers

A setting modifier is based off of `Magium\Magento\Actions\Admin\Configuration\SettingModifier`.  it is based purely off of the notion that working with the System Configuration sucks.  What it does is take a setting (from you) and set its value.

A setting modifier has two ways the setting can be specified.

1. via its ID (every element in the System Configuration has an ID (this is actually really nice))
2. via its label.

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

## Implementation

There are a lot of configuration options and so automating the whole thing will take some time.  If you want to just make some configuration changes, here is some code that will help.

```
<?php

namespace Examples\Actions\Admin\Configuration;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Configuration\SettingModifier;
use Magium\Magento\Actions\Admin\Login\Login;
use Magium\Magento\Navigators\Admin\AdminMenu;

class ArbitraryConfigurationChangesTest extends AbstractMagentoTestCase
{

    public function testArbitrarySettingChange()
    {
        $this->commandOpen($this->getTheme('Admin\ThemeConfiguration')->getBaseUrl());
        $this->getAction(Login::ACTION)->login();
        $this->getNavigator(AdminMenu::NAVIGATOR)->navigateTo('System/Configuration');
        $this->getAction(SettingModifier::ACTION)->set(
            'General/Store Information::label=Store Name',
            'My Store'
        );
        // The "Save" button is not clicked by default, but we can force it by setting the third argument to "true"
        $this->getAction(SettingModifier::ACTION)->set(
            'General/Store Information::label=Store Contact Telephone',
            '123-123-1234',
            true
        );
    }

}
```