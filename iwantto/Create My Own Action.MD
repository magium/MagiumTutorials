# I Want To Create My Own Action

An action is used when you want to do something.  "You mean like browse to a category?"  No, that is when you want to go somewhere.  Actions are for when you want to do something, most likely cause a change to occur.  Search, login, checkout.  All require some level of input to cause a change.  That is what an action is for.

There is no required class to extend to build an action.  There may be a single action, multiple actions, or setters that need to be called.  As a general matter of practice they should have an `execute()` method defined, but this is not required.

Actions should follow the SOLID principles (Single Responsibility, Open/Closed Principle, Lyskov Substitution, Interface Segregation, Dependency Inversion).  The most important of these is the Single Responsibility principle.

Why is the Single Responsibility principle important?  Because it allows you to re-use that functionality in other places and reduces the scope of breakability.

There are 2-4 items that you will probably require, at a minimum.

1. The WebDriver (to do stuff)
2. (a really good idea) The Theme (to get configuration)
3. (optionally) The current test case (for assertions)
4. Assertions to ensure that the action worked
     * Often all you need to do is allow a selector to fail to cause the test to fail.  WebDriver throws an exception if the element cannot be found.

An action should always be retrieved through `AbstractTestCase::getAction()` that is a convenience method that automatically goes through the dependency injection container and satisifies the dependencies for the object you are trying to create.  In other words you never ask for ab object (like `Mage::getModel()`) you declare what you might need and the DIC will provide it for you.

Let's take a look at what an entire solution, which covers your own actions (not core actions) will look like.  (If you are adding to core functionality you can omit the part about having your own theme class (AND do a pull request to [magium/MagiumMagento](https://github.com/magium/MagiumMagento) to add that core functionality))

```
<?php

namespace Examples\Actions;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\WebDriver\WebDriver;

class SubscribeToNewsletterTest extends AbstractMagentoTestCase
{

    public function testSubscribeToNewsletter()
    {
        self::addBaseNamespace('Examples');
        $this->switchThemeConfiguration('Examples\Actions\ThemeConfiguration');
        $this->commandOpen($this->getTheme()->getBaseUrl());

        $identity = $this->getIdentity();
        /* @var $identity Customer */
        $identity->generateUniqueEmailAddress();

        $action = $this->getAction(SubscribeToNewsletter::ACTION);
        $action->subscribe($identity->getEmailAddress());

    }

}

class SubscribeToNewsletter
{

    const ACTION = 'SubscribeToNewsletter';

    protected $theme;
    protected $webDriver;
    protected $testCase;

    public function __construct(
        WebDriver $webDriver,
        ThemeConfiguration $themeConfiguration,
        AbstractMagentoTestCase $testCase
    )
    {
        $this->webDriver = $webDriver;
        $this->theme = $themeConfiguration;
        $this->testCase = $testCase;
    }

    public function subscribe($emailAddress)
    {
        $this->testCase->assertElementDisplayed($this->theme->getNewsletterEmailId());
        $this->testCase->assertElementDisplayed($this->theme->getNewsletterSubscribeXpath(), WebDriver::BY_XPATH);

        $emailElement = $this->webDriver->byId($this->theme->getNewsletterEmailId());
        $emailElement->clear();
        $emailElement->sendKeys($emailAddress);

        $subscribeElement = $this->webDriver->byXpath($this->theme->getNewsletterSubscribeXpath());
        $subscribeElement->click();

        $this->testCase->assertElementExists(
            $this->theme->getNewsletterSubscribeSucceededXpath(),
            WebDriver::BY_XPATH
        );
    }
}

class ThemeConfiguration extends \Magium\Magento\Themes\Magento19\ThemeConfiguration
{

    protected $newsletterEmailId = 'newsletter';

    protected $newsletterSubscribeXpath = '//button[@title="{{Subscribe}}"]';

    protected $newsletterSubscribeSucceededXpath = '//li[@class="success-msg"]/descendant::span[.="{{Thank you for your subscription.}}"]';

    /**
     * @return string
     */
    public function getNewsletterEmailId()
    {
        return $this->newsletterEmailId;
    }

    /**
     * @return string
     */
    public function getNewsletterSubscribeXpath()
    {
        return $this->translatePlaceholders($this->newsletterSubscribeXpath);
    }

    /**
     * @return string
     */
    public function getNewsletterSubscribeSucceededXpath()
    {
        return $this->translatePlaceholders($this->newsletterSubscribeSucceededXpath);
    }

}
```
([source](../examples/Actions/SubscribeToNewsletterTest.php))

That's a lot to take in.  Let's break it down into components.

## Theme

In this example we created a new theme class to contain the selectors needed for the action to succeed.  There were three selectors

1) Email address element ID
2) Subscribe button Xpath
3) Subscription succeeded Xpath

"Wait a sec!" you might be saying.  "Didn't you say that we shouldn't use IDs in themes?"  Yes, for core functionality.  If it is for your own website use the most specific selector possible and you cannot get any more specific than an XML ID.  If you were to contribute this code to the core you would be asked to change that to the Xpath `//input[@id="newsletter"]` so someone else could change it without having to change the action code.

All configuration data is retrieved via getters.  That is because the theme might need to do some processing.  It is better to standardize on an approach and so we are standardizing on getters because it allows you to retrieve data that requires processing in the same way for data that does not.  Consistency, in other words.

But also, two of the three items do need processing.  Because there is text involved it is a really good idea to pass it through the translator prior to returning it to the action.  **The action should always receive back a usable Xpath from the theme.**

## Action

The action is fairly straightforward.

1) Validate that the elements it needs are displayed on the screen
2) Enter the email address
3) Click "Subscribe"
4) Ensure that the subscription succeeded
    * There may be times when you want to validate that a subscription fails.  There are two ways of doing this
        1) Inject an assertion into the action
        2) Call `setExpectedException('PHPUnit_Framework_ExpectationFailedException')` in the test case, though this may allow other failed assertions to give a false positive

It has a start and finish defined by its purpose: "subscribe to the newsletter".

Note that the constant `ACTION` is defined so code completion can be used to get the action instance.

## Test Case

The test case has a few things worth mentioning.

* All classes are being executed in the `Example\Actions` namespace.  We need to call `addBaseNamespace()` to add the name of the namespace we are working in.  The autoloader may not get the correct classname if we do not.
* Technically, in this example, we didn't need to switch the theme configuration because the action explicitly requests `Example\Actions\ThemeConfiguration`.  But given that themes tend to be global we put it in here as a matter of best practice.  Though that best practice is also not quite followed since the proper place to do it is in its own abstract test case class in the `setUp()` method.  But then things get a little too complicated.
* The customer identity is used simply because it has functionality to generate a unique email address.