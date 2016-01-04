<?php

namespace Examples\Identities;

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