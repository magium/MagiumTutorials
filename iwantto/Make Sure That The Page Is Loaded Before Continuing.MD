# I Want To Make Sure That The Page Is Loaded Before Continuing

*Most of the time* this should happen automatically.  However there seem to be some times, most notably with Firefox, where `click()` (and other) calls return prior to the page being completely rendered.

An action called `WaitForPageLoaded` can be used to resolve this issue. It requires a theme configuration setting to find an element that is at, or near, the end of the page, or, will guarantee that the page is ready when the element is displayed.

The theme method to get the Xpath for this functionality is called `getGuaranteedPageLoadedElementDisplayedXpath()`.  It is part of the base `ThemeConfigurationInterface` and so must be present on any page.  You can also call `setGuaranteedPageLoadedElementDisplayedXpath($xpath)` if you need to temporarily change the Xpath.

 There are two options for using the `WaitForPageLoaded` action.

 ## Basic Usage

 The basic usage will give you reasonable assurance that the page has actually loaded.  It basically goes:

1. Wait until the guaranteed element exists
2. Wait until the guaranteed element is displayed

It looks something like this

```
class Test extends AbstractMagentoTestCase
{
    public function testBasic()
    {

        $element = $this->byId('test-button');
        $element->click();
        $this->get(WaitForPageLoaded::ACTION)->execute();

    }
}
```

Most of the time that is sufficient.  But there could be times when the browser rendering is out of sync with the Selenium Server and so there may be a fraction of a second where the `execute()` call returns successfully but it is still on the original page.

To be absolutely sure you should use the advanced usage.

## Advanced Usage

Advanced usage adds an additional step where it verifies that the element from the original page is actually disconnected before checking the test element's visibility.

The process is this:

1. Wait until the element is disconnected from the page (meaning that the original page is actually gone)
2. Wait until the guaranteed element exists
3. Wait until the guaranteed element is displayed

It looks something like this.

```
class Test extends AbstractMagentoTestCase
{
    public function testBasic()
    {

        $element = $this->byId('test-button');
        $element->click();
        $this->get(WaitForPageLoaded::ACTION)->execute($element);

    }
}
```

Because `$element` is part of the original page it can be used as a test element to verify that the page has actually started a navigation.