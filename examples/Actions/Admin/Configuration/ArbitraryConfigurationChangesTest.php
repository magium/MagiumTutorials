<?php

namespace Examples\Actions\Admin\Configuration;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Configuration\Enabler;
use Magium\Magento\Actions\Admin\Configuration\SettingModifier;
use Magium\Magento\Actions\Admin\Login\Login;
use Magium\Magento\Navigators\Admin\AdminMenu;

class ArbitraryConfigurationChangesTest extends AbstractMagentoTestCase
{

    public function testArbitrarySettingChange()
    {
        $this->commandOpen($this->getTheme('Admin\ThemeConfiguration')->getBaseUrl());
        $this->getAction(Login::ACTION)->login();
        $this->getNavigator(AdminMenu::NAVIGATOR)->navigateTo('System/Configuration');
        $this->getAction(SettingModifier::ACTION)->set(
            'General/Store Information::label=Store Name',
            'My Store'
        );
        // The "Save" button is not clicked by default, but we can force it by setting the third argument to "true"
        $this->getAction(SettingModifier::ACTION)->set(
            'General/Store Information::label=Store Contact Telephone',
            '123-123-1234',
            true
        );
    }

}