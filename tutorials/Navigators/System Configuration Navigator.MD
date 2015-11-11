System Configuration Navigator
==============================

The system configuration navigator will navigate to any System Configuration section.  To do so you simply provide the name of the section you want to, starting with the tab, and call navigateTo().

For example, if you wanted to make changes to the Saved CC payment method you would use the [following code](../../examples/Navigators/SystemConfigurationNavigatorTest.php) to get there.

```php
class SystemConfigurationNavigatorTest extends AbstractMagentoTestCase
{

    public function testAdminMenuNavigation()
    {
        $this->getAction('Admin\Login\Login')->login(); // Remember to set the test username and password via an identity
        $this->getNavigator('Admin\AdminMenuNavigator')->navigateTo('System/Configuration');
        $this->getNavigator('Admin\SystemConfigurationNavigator')->navigateTo('Payment Methods/Saved CC');
        $this->assertElementDisplayed('payment_ccsave_active');
    }

}
```

A few things to note about this example

1. Remember that SOLID is going to be followed as much as is useful and so the system configuration navigator will only have instructions for navigating the configuration tabs/sections and so you will need to use the menu navigator to get there first.
2. The system configuration navigator will check to see if the Saved CC section is open.  If it is not open it will click on its header.  If it is open it will not do anything.