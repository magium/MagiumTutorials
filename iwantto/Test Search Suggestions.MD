# I Want To Test Search Suggestions

Search suggestions are tested through the use of the search action and the search suggestion extractor.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Search\Search;
use Magium\Magento\Extractors\Catalog\Search\SearchSuggestions;

class SearchTest extends AbstractMagentoTestCase
{

    public function testSearchSuggestions()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(Search::ACTION)->type('sh');
        $extractor = $this->getExtractor(SearchSuggestions::EXTRACTOR);
        $extractor->extract();

        $suggestions = $extractor->getSuggestions();

        self::assertGreaterThan(0, count($suggestions));
        self::assertGreaterThan(0, $suggestions[0]->getCount());
        self::assertContains($this->partialSearch, $suggestions[0]->getText());

        // Search suggestions should have a clickable element
        $suggestions[0]->getElement()->click();
    }

}
```