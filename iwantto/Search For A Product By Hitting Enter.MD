# I Want To Search For A Product By Hitting Enter

This is kind of a cheat, but it works.  You will need the search action, but instead of using the `search()` method, which will actually click the search button, you need to append the `ENTER` key to the string you are going to search for.

```
<?php

use Facebook\WebDriver\WebDriverKeys;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Search\Search;

class SearchTest extends AbstractMagentoTestCase
{

    public function testSearchWithEnter()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(Search::ACTION)->type('shirts' . WebDriverKeys::ENTER);

    }

}
```