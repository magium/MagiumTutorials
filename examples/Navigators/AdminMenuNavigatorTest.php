<?php

namespace Examples\Navigators;

use Magium\Magento\AbstractMagentoTestCase;

class AdminMenuNavigatorTest extends AbstractMagentoTestCase
{

    public function testAdminMenuNavigation()
    {
        $this->getAction('Admin\Login\Login')->login(); // Remember to set the test username and password via an identity
        $this->getNavigator('Admin\AdminMenuNavigator')->navigateTo('Sales/Orders');
        $this->assertPageHasText('Select Visible');
    }

}