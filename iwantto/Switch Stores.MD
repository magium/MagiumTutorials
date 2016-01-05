# I Want To Switch Stores

There is a simple mechanism for switching in between stores.  It is called `Magium\Magento\Navigators\Store\Switcher` and it extends the `InstructionNavigator`.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Navigators\Store\Switcher;

class StoreSwitcherNavigationTest extends AbstractMagentoTestCase
{

    public function testStoreSwitcherNavigation()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $element = $this->webdriver->byXpath('//body');

        $this->getNavigator(Switcher::NAVIGATOR)->switchTo('german');

        // verifies that we've actually switched the page
        self::assertFalse($this->webdriver->elementAttached($element));
    }

}
```

Note that the method `navigateTo()` is available, but don't use it.  Use `switchTo($store)` instead.  Note, also, that you need to use the store code, not the label for the switch.