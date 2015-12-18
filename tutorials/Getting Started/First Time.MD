Running Magium The First Time
=============================

The goal of this document is to help you get Magium (using the Magento implementation) up and running 3 basic tests in about 20-30 minutes.

Section 1 - Getting things set up
---------------------------------

* Step 1 - Download, install and run Selenium with the chromedriver plugin

  [Download Selenium Server](http://www.seleniumhq.org/download/)

  [Download chromedriver](http://chromedriver.storage.googleapis.com/index.html)

  Extract it into the Selenium Server directory.

  Then execute

  `java -Dwebdriver.chrome.driver=chromedriver.exe -jar selenium-server-standalone-2.48.2.jar`

* Step 2 - Create a new PHP project
* Step 3 - Initialize Composer
* Step 4 - Require magium/magento (`composer require magium/magento`)
* Step 5 - Ensure that PHPUnit works according to your normal development practices
* Step 6 - Make sure that you have Magento CE 1.9 installed with the sample data

Section 2 - A basic test to make sure everything is working
-----------------------------------------------------------

All test classes need to extend Magium\AbstractTestClass, which itself extends PHPUnit_Framework_TestCase.  This base class contains some basic bootstrapping, such as configuring WebDriver, along with helper methods for commonly use functionality.

Typically a Magium sub-project will have its own abstract test case to define the software's unique characteristics.  For Magento that class is Magium\Magento\AbstractMagentoTestCase.

Create a new test class with the following code, replacing the hostname, of course.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\WebDriver\WebDriver;

class BasicTest extends AbstractMagentoTestCase
{

    public function testPageloads()
    {
        $this->commandOpen('http://localhost/');
        $this->assertElementExists('//body', WebDriver::BY_XPATH);
    }

}
```
There are a few things to note about this code:

* commandOpen() - opens a URL
* assertElementExists() - Will check to see if the element requested with the provided selector exists
* WebDriver::BY_XPATH - a convenience constant that tells the assertion which selector to use.  There are ID, Xpath and CSS selectors available.  Almost all convenience methods default to using the ID selector

With that you should have a basic test that passes.

Section 2 - A test with a configured theme

This next test will do exactly the same thing as the previous test, but will do so by introducing two new concepts: configurable items and themes.

A "theme" is more than just a theme.  It contains any configuration modifications unique to your setup required to handle core functionality.  For example, your mechanism to navigate to the customer login page might be different from the base rwd theme.

However, for the time being we are going to look at the other concept; configurable items.  Themes in Magento can both extend base theme classes and have configurable properties.  We are only going to look at configuring the theme, not overriding base classes.

Classes that are configurable will need their configuration stored in a /configuration directory as the base, followed by the namespace hierarchy as the directory under that followed by the filename matching the class.

That sounds complicated, but what it boils down to is that to configure the class `Magium\Magento\Themes\Magento19\ThemeConfiguration` you would create a file called `/configuration/Magium/Magento/Themes/Magento19/ThemeConfiguration.php`.  This does not work for all classes, only classes that extend `Magium\AbstractConfigurableElement`.

The configuration file is simply included as part of the class' constructor.  For that reason the configuration file for this test will look like:

```
<?php

$this->baseUrl = 'http://localhost/'; // Change to yours, obviously
```

Why do it this way?  Because with Magium you are free to make your own decision how those values are configured.  They could be hard coded like this, you could extract them from environment variables, or you could pull it from PRNG for all I care.

But how do you know what it should be?  Open `Magium\Magento\Themes\Magento19\ThemeConfiguration` ands take a look around.  Modifying themes may require some hunting around, but once they are configured you shouldn't have to think about them again.  (I'm work on a way to make this easier).

Next up is to create a test that uses that theme class instead of hard coding the URL.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\WebDriver\WebDriver;

class ThemeTest extends AbstractMagentoTestCase
{

    public function testPageloads()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->assertElementExists('//body', WebDriver::BY_XPATH);
    }

}
```

Section 3 - Adding to cart and checking out
-------------------------------------------

This final exercise will only work with the sample 1.9 data.  It can be configured to any scenario. But for the same of simplicity you should use the standard sample data.  The purpose of this final exercise is to give you a test of the power Magium has to streamline standard operations.

First, make sure your Magento instance is configured to use Cash On Delivery as the payment method and that guest checkout is allowed.

Second, run the following code.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;

class CheckoutTest extends AbstractMagentoTestCase
{

    public function testCheckout()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(AddItemToCart::ACTION)->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
        $this->getAction(GuestCheckout::ACTION)->execute();

        $orderId = $this->getExtractor(OrderId::EXTRACTOR);

        self::assertNotNull($orderId->getOrderId());
    }

}
```

This code will

* Open up the current theme
* Navigate to a default category
* Add a product to the shopping cart
* Navigate to the shopping cart
* Select guest checkout
* Enter in the billing information
* Select the first available shipping method
* Select the Cash On Delivery payment method
* Submit the order
* Extract the order ID
* Assert that the order ID is not null

... in 6 lines of code