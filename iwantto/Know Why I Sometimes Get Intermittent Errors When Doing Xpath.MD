# I Want To Know Why I Sometimes Get Intermittent Errors When Doing Xpath

Most likely it is because the browser is going slower than Selenium or vice versa.  9 times out of 10 the solution is to add a `wait()` command to the test.  Something along the lines of this:

```
use Magium\Magento\AbstractMagentoTestCase;
use Magium\WebDriver\ExpectedCondition;

class MyTest extends AbstractMagentoTestCase
{
    public function testWait()
    {
        $this->webDriver->wait()->until(ExpectedCondition::elementExists('//xpath', WebDriver::BY_XPATH));
    }

}
```

There are a number of conditions available in the base `WebDriver\WebDriver\WebDriverExpectedCondition` class and `Magium\WebDriver\ExpectedCondition` simply extends and adds to it.  They are basically callbacks that WebDriver will use to pause until the condition is satisfied.  See the [`ExpectedCondition`](https://github.com/magium/Magium/blob/master/lib/WebDriver/ExpectedCondition.php) class for additional details.