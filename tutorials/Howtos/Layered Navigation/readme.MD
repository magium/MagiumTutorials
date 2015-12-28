Getting Started With Layered Navigation
=======================================

Layered Navigation might be one of the harder components to work with.  A big part of the problem is that different themes use different CSS styles are used.  Normally that isn't much of a problem but because there is interaction between the navigation filters and the Layered Navigation extractor CSS case sensitivity may be a problem.  The text in the HTML may be "abc" but because of CSS `getText()` will return "ABC".  But the Xpath selector requires "abc".  You could normalize the values but there's no guarantee that the normalization will be followed by the designers.  For that reason the pertinent HTML is extracted from HTML document and Xpath'ed without CSS using `\DOMDocument` and `\DOMXPath`.

 **If you are a non-developer, or you are not concerned about modifying Layered Navigation extractor classes then you could have ignored that paragraph**

There are a few concepts you will need to take into account when working with Layered Navigation:

* The Layered Navigation extractor - This is the base class that will actually do the extraction
* Filters - These are the navigation filters on your Magento website; things like "Price", "Occasion", etc.
* Values - These are the options for each filter.  For example "$100-$199.99" for the price filter

Let's start with a basic user story.

`As a male shopper I want to browse casual shirts`

To satisfy this user story we would write a test like this:

```
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
```
[(source)](../../../howto/Layered Navigation/MaleShopperBrowseCasualShirtsTest.php)

Most layered navigation components can be handled using the default filter, creatively called `Magium\Magento\Extractors\Catalog\LayeredNavigation\FilterTypes\DefaultFilter`.  All filters will extend `Magium\Magento\Extractors\Catalog\LayeredNavigation\FilterTypes\AbstractFilterType`.

However, there are times when the default filter will not be sufficient.  For example, if you are using configurable swatches and your user story is

`As shopper I want to find Indigo jewelry`

Configurable swatches can have images for their layered navigation filters which means that standard text selectors will not work.  For that reason there is a filter called `Magium\Magento\Extractors\Catalog\LayeredNavigation\FilterTypes\SwatchFilter`.

> This brings up an interesting question: "how do you choose which filter to use?"  The answer is that you don't.  Each filter has a method called `filterApplies()` which the layered navigation extractor calls to see if the filter class applies to a given filter.  All you need to do is request the (case-insensitive) name of the filter.

So if you are using CE 1.9, and using configurable swatches you can use code like the following to satisfy the previous user story.

```
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
```
[(source)](../../../howto/Layered Navigation/ShopperWantsToFindIndigoJewelryTest.php)

Prices are another thing that have some additional functionality that you might want to use.  Consider the following user story:

`As a shopper I want to find women's boots over $400`

Here is the code you would write for that story.

```
<?php

use Magium\Magento\Navigators\BaseMenu;
use Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation;

class ShopperWantsToFindBootsOver400 extends \Magium\Magento\AbstractMagentoTestCase
{
    public function testShopperWantsToFindBootsOver400()
    {

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(BaseMenu::NAVIGATOR)->navigateTo('Accessories/Shoes');
        $layeredNav = $this->getExtractor(LayeredNavigation::EXTRACTOR);
        /* @var $layeredNav \Magium\Magento\Extractors\Catalog\LayeredNavigation\LayeredNavigation */
        $layeredNav->extract();

        $layeredNav->getFilter('Price')->getValueForPrice(400);
    }
}
```
[(source)](../../../howto/Layered Navigation/ShopperWantsToFindIndigoJewelryTest.php)

Now, obviously simply knowing that the filter exists may not be enough.  You might want to click on a link as part of the story to, maybe, add a product to the shopping cart.  This functionality is built into each value object.  Each value object contains a `WebDriverElement` object for the `a` tag that it links to.  So to click on that link we just need to append some code to the end of the previous test.

```
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
```
[(source)](../../../howto/Layered Navigation/ShopperWantsToFindBootsOver400WithClick.php)