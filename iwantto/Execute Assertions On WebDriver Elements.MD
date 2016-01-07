# I Want To Execute Assertions On WebDriver Elements

Assertions can happen anywhere a test case exists.  For that reason you will often find actions, navigators and extractors defined with the test case.  The test case contains the functionality for doing assertions.

Why?  Well, honestly, because that's how PHPUnit does it.  Assertions are bound to the test case.  But we run into a bit of a problem from an architectural perspective because there are a lot of dependent classes that Magium tests use.  And so PHPUnit goes well beyond Single Responsibility with the Test Case class.

Magium adds several addition assertions that can be made.  Here is a little bit of a thing you need to watch for.  Generally speaking the assertions will use the static assertion approach, just as PHPUnit does.  Think `self::assertTrue(true)`.

But several of the assertions require the WebDriver object and we really don't have a way of dealing with that in the static context without possibly affecting subsequent tests, if there's an error.

 So some assertions are done in the static context, others in the object context.  Since this article is looking at items that require a WebDriver object, all will be in the object context of a `Magium\AbstractTestCase` (which also includes, by extension `Magium\Magento\AbstractMagentoTestCase`).

Also note that, by default, the HTML ID is used to do the assertion.  If you are passing in Xpath you will need to add a second argument which denotes which selector method is used.  It can be:

* `WebDriver::BY_ID`
* `WebDriver::BY_CSS_SELECTOR`
* `WebDriver::BY_XPATH`

```
use \Magium\WebDriver\WebDriver;

$this->assertElementExists('//div[.="test"]', WebDriver::BY_XPATH);
```

| Method | Purpose|
| ----- | ------ |
| $this->assertElementExists('select1') | Asserts that the element exists on the page  |
| $this->assertElementNotExists('select0000') | Asserts that element does not exist on the page |
| $this->assertElementDisplayed('select1') | Asserts that the element exists and is on the page |
| $this->assertElementNotDisplayed('hiddenElement') | Asserts that the element exists, but is **not** on the page |
| $this->assertTitleEquals('Test Title') | Asserts that the title is *exactly* what is requested (as with all title assertions, this is not translated, though this could be changed) |
| $this->assertTitleContains('Title') | Asserts that the title contains the words requested|
| $this->assertNotTitleEquals('My little buttercup') | Asserts that the title does not equal the provided text |
| $this->assertNotTitleContains('My little buttercup') | Asserts that the title does not contain the provided text |
| $this->assertPageHasText('Text') | Asserts that the HTML `BODY` element contains the requested text |
| $this->assertPageNotHasText('My little buttercup') | Asserts that the HTML `BODY` element does **not** contain the provided text |

