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