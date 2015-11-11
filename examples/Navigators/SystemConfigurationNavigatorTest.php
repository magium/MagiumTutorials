<?php

namespace Examples\Navigators;

use Magium\Magento\AbstractMagentoTestCase;

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