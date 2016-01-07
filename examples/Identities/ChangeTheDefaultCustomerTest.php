<?php

namespace Examples\Identities;

use Magium\Magento\AbstractMagentoTestCase;


class ChangeTheDefaultCustomerTest extends AbstractMagentoTestCase
{
    public function testCustomerLogin()
    {
        $this->setTypePreference('Magium\Magento\Identities\Customer', 'Examples\Identities\Customer');
        self::assertInstanceOf('Examples\Identities\Customer', $this->getIdentity());
    }
}

class Customer extends \Magium\Magento\Identities\Customer
{

    public function doSomethingAwesome()
    {
        // You can use your imagination
    }

}



use Magium\Magento\Themes\Magento19\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class MyTheme extends ThemeConfiguration
{

    public function getSomeId()
    {
        return 'some_id';
    }

}

class MyUsefulNavigator
{

    protected $webDriver;
    protected $theme;

    public function __construct(
        WebDriver $webDriver,
        MyTheme $theme)
    {
        $this->webDriver = $webDriver;
        $this->theme = $theme;
    }


    public function navigateTo()
    {
        $this->webDriver->byId($this->theme->getSomeId())->click();
    }

}
