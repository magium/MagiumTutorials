# I Want To Speed Up SELECT Calls

Operations on SELECT are somewhat slow when there are large numbers of OPTIONs as part of the select.  The Magento System Configuration is a good example of this because there are often multiple countries in a SELECT list.  To help out with the performance issues Magium has the option of using a `FastSelect` object instead.

The Fast SELECT class runs JavaScript on the page to work with the options and the result is at least a 10-fold improvement in performance.

```
<?php

use Magium\AbstractTestCase;
use Magium\WebDriver\FastSelectElement;

class FastSelectTest extends AbstractTestCase
{

    public function testSelect()
    {
        $fast = new FastSelectElement($this->webdriver, '//select[@id="select"]');
        $result = $fast->getOptions();


        $this->webdriver->byXpath('//*[@id="select"]/option[1]')->click();
        $result = $fast->getSelectedOptions();

        $fast->clearSelectedOptions();
        $result = $fast->getSelectedOptions();

    }

}
```
