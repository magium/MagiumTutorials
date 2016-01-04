<?php

namespace Examples\Actions\Admin\Configuration;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Configuration\Enabler;
use Magium\Magento\Actions\Admin\Login\Login;
use Magium\Magento\Navigators\Admin\AdminMenu;

class EnablePaymentMethodTest extends AbstractMagentoTestCase
{

    public function testEnablePaymentMethod()
    {
        $this->commandOpen($this->getTheme('Admin\ThemeConfiguration')->getBaseUrl());
        $this->getAction(Login::ACTION)->login();
        $this->getNavigator(AdminMenu::NAVIGATOR)->navigateTo('System/Configuration');
        $this->getAction(Enabler::ACTION)->enable('Payment Methods/My Payment Method');
    }

}