# I Want To Go To The Home Page

 This requires a simple navigator.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Navigators\Cart\DefaultProductCategory;
use Magium\Navigators\Home;

class HomeNavigationTest extends AbstractMagentoTestCase
{

    public function testHomeNavigation()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(DefaultProductCategory::NAVIGATOR)->navigateTo();
        $this->getNavigator(Home::NAVIGATOR)->navigateTo();
    }

}
```

However, there is a little more to it than that (as I'm sure you can imagine).  This requires a theme configuration setting.  By default it will go to the Xpath `//a[@class="logo]`.  If you have something different you will need to override it via the configuration

```
<?php

$this->homeXpath = '//a[.="Home"]'; // or something like this
```
/configuration/Magium/Magento/Themes/Magento19/ThemeConfiguration.php