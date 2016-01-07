# I Want To Search For A Product

Searching for a product is done through the use of the search action combined with the search result extractor.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Search\Search;
use Magium\Magento\Extractors\Catalog\Search\SearchSuggestions;

class SearchTest extends AbstractMagentoTestCase
{
    public function testSearch()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(Search::ACTION)->search('search');
        $extractor = $this->getExtractor(\Magium\Magento\Extractors\Catalog\Search\SearchResults::EXTRACTOR);
        /* @var $extractor \Magium\Magento\Extractors\Catalog\Category\Search */
        $extractor->extract();
        $productList = $extractor->getProducts();
        // Do assertions
    }
}
```