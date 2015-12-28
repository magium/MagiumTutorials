<?php

use Magium\Magento\Navigators\BaseMenu;
use Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation;

class ShopperWantsToFindBootsOver400WithClick extends \Magium\Magento\AbstractMagentoTestCase
{
    public function testShopperWantsToFindBootsOver400WithClick()
    {

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(BaseMenu::NAVIGATOR)->navigateTo('Accessories/Shoes');
        $layeredNav = $this->getExtractor(LayeredNavigation::EXTRACTOR);
        /* @var $layeredNav \Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation */
        $layeredNav->extract();

        $value = $layeredNav->getFilter('Price')->getValueForPrice(400);
        $value->getElement()->click();
    }
}