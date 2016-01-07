# I want To Browse To A Product Page

 Browsing to a product page is quite simple.  Magento has a default `a` pattern that it follows and so we can just get the appropriate navigator ([once we are on a page with displayed products](Browse To A Category.MD))

 ```
 <?php

 use Magium\Magento\AbstractMagentoTestCase;
 use Magium\Magento\Navigators\Catalog\Product;

 class ProductNavigationTest extends AbstractMagentoTestCase
 {

     public function testNavigateToJewelry()
     {
         // open URL
         $this->commandOpen($this->getTheme()->getBaseUrl());
         // Navigate to category
         $this->getNavigator()->navigateTo($theme->getNavigationPathToProductCategory());
         // Navigate to the product
         $this->getNavigator(Product::NAVIGATOR)->navigateTo('Pearl Stud Earrings');
     }
 }
 ```