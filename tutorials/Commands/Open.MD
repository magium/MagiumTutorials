CommandOpen
===========

There are very few commands that are made available in Magium as separate functionality from WebDriver.  There are commands, such as clicks, moves and such but those are usually done including the context of a particular element.  For example, _most_ of the time you will not be clicking on X = 45, Y = 123, you will be clicking on element XYZ which happens to be there.

One of the few (possibly the only) time when you really need to execute an action on WebDriver not bound to another element is when you want to open a given web page.  In addition, pretty much every test will require a web page which means that this command will probably be one of the few consistent pieces of functionality across all tests.

```php
namespace Tests\Magium\Command;

use Magium\Magento\AbstractMagentoTestCase;

class CommandOpenTest extends AbstractMagentoTestCase
{

    public function testCommandOpenProvidesATitle()
    {
        $this->commandOpen('http://magento19.loc/'); // Change to your website.
        self::assertEquals('Madison Island', $this->webdriver->getTitle());
    }

}
```