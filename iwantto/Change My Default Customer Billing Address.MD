# I Want To Change My Default Customer Billing Address

This requires a little bit of skill at the moment, though not much.  While Magium is intended to script as much as possible there are some things that just are not used that often and so are not high on the list of priorities.  But there are still a number of things that Magium can do to make it easier to manage "more manual" tests.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Customer\NavigateAndLogin;
use Magium\Magento\Navigators\Customer\Account;

class ChangeDefaultCustomerBillingAddressTest extends AbstractMagentoTestCase
{

    public function testChangeDefaultCustomerBillingAddress()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(NavigateAndLogin::ACTION)->login();
        $this->getNavigator(Account::NAVIGATOR)->navigateTo('{{Address Book}}');
        $this->byText('{{Change Billing Address}}')->click();

        $this->byId('telephone')->clear();
        $this->byId('telephone')->sendKeys('123-123-1234');

        $this->byId('street_1')->clear();
        $this->byId('street_1')->sendKeys('123 My St');

        $this->byId('city')->clear();
        $this->byId('city')->sendKeys('My Town');

        $this->byText('{{Texas}}')->click(); // Selects the OPTION with the label "Texas"

        $this->byId('zip')->clear();
        $this->byId('zip')->sendKeys('90210');

        $this->byText('{{Use as my default billing address}}')->click();

        $this->byXpath('//button[.="Save Address"]');

        self::assertEquals('123 My St', $this->byId('street_1')->getAttribute('value'));
    }

}
```
([Source](../examples/Identities/ChangeDefaultCustomerBillingAddressTest.MD))

There are a few things to note here.

The first is that we are making liberal use of `getText()`.  This can be a very useful shortcut to not having to use Xpath in your testing code.  It will not match any text, but only text that is exact, and within a specific set of possible elements (`a`, `li`, `span`, `label`, `option`).

The second is that if you look at byText() the translator is specified but "Save Address" is not.  [See here for an explanation](Know Why byText Has Translation Support and byXpath does not.MD).