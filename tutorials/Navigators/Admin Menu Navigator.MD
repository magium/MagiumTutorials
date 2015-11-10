Admin Menu Navigator
====================

The `AdminMenuNavigator` is virtually identical to the [BaseMenuNavigator](Base Menu Navigator.MD).  In fact, the only difference is in the constructor where it defines the `AdminThemeConfiguration` object as a dependency instead of `ThemeConfiguration`.

Retrieving an instance of `AdminMenuNavigator` can be done by requesting it from the `getNavigator()` method, passing in `'AdminMenuNavigator'` as a parameter.

```php
class AdminMenuNavigatorTest extends AbstractMagentoTestCase
{

    public function testAdminMenuNavigation()
    {
        $this->getAction('Admin\Login\Login')->login(); // Remember to set the test username and password via an identity
        $this->getNavigator('Admin\AdminMenuNavigator')->navigateTo('Sales/Orders');
        $this->assertPageHasText('Select Visible');
    }

}
```

The full example is available in [this repository](../../examples/Navigators/AdminMenuNavigatorTest.php).