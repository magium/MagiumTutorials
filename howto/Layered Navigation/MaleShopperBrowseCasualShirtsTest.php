<?php

use Magium\Magento\Navigators\BaseMenu;
use Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation;

class MaleShopperBrowseCasualShirtsTest extends \Magium\Magento\AbstractMagentoTestCase
{
    public function testMaleShopperBrowseCasualShirts()
    {
        // Go to the default URL
        $this->commandOpen($this->getTheme()->getBaseUrl());
        // Navigate to the category Men/Shirts (change for your own setup)
        $this->getNavigator(BaseMenu::NAVIGATOR)->navigateTo('Men/Shirts');
        // Request the layered nav extractor
        $layeredNav = $this->getExtractor(LayeredNavigation::EXTRACTOR);
        /* @var $layeredNav \Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation */
        // Extract
        $layeredNav->extract();
        /*
         * Get the Casual value for the Occasion filter.  Both of these methods will throw exceptions if  the
         * argument provided does not match, causing the test to fail
         */
        $layeredNav->getFilter('Occasion')->getValueForText('Casual');

    }
}