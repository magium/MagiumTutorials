# I Want To Log In As A Customer

This requires 2-3 components

1. The Identity to log in as
2. A Navigator that navigates to the login screen
3. An Action that does the logging in

*Why 2-3?  Because there is a `NavigateAndLogin` navigator available*

```
<?php


use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Customer\Login;
use Magium\Magento\Navigators\Customer\AccountHome;

class ToCustomerLoginTest extends AbstractMagentoTestCase
{


    public function testLoginCustomer()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(AccountHome::NAVIGATOR)->navigateTo();
        $this->getAction(Login::ACTION)->login();
        self::assertEquals('My Account', $this->webdriver->getTitle());
    }
}
```