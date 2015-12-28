<?php

use Magium\Magento\Navigators\BaseMenu;
use Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation;

class ShopperWantsToFindIndigoJewelry extends \Magium\Magento\AbstractMagentoTestCase
{
    public function testShopperWantsToFindIndigoJewelry()
    {

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(BaseMenu::NAVIGATOR)->navigateTo('Accessories/Jewelry');
        $layeredNav = $this->getExtractor(LayeredNavigation::EXTRACTOR);
        /* @var $layeredNav \Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation */
        $layeredNav->extract();

        $layeredNav->getFilter('Color')->getValueForText('Indigo');

    }
}