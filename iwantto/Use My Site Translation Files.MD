# I Want To Use My Site Translation Files

Translation is important to any international site.  And so translation is built into Magium from the ground up.  (It's also still a little warm and so feedback would be appreciated)

The translation adapter needs to be set up prior to executing any tests.  More accurately, it needs to be set up prior to doing any translations.

There are three things you need to know about when working with translations

1) The translation adapter
2) The translation files with their corresponding locale (configuration, in other words)
3) Themes

## The Translation Adapter

The translation adapter is the class `Magium\Util\Translator\Translator` and is built off of the Zend Framework component `Zend\I18n\Translator\Translator`.  However, if things are working well that should never matter to you.

 When in the test case class or in an object that has defined the translator as a dependency there are two ways of translating data

 ### Direct Translation

Direct translation is when you ask the translator directly for the translated value.

```
use Magium\AbstractTestCase;

class TranslatorTest extends AbstractTestCase
{
    public function testTranslator()
    {
        $translator = $this->getTranslator();
        self::assertEquals('Test Value', $translator->translate('Test Value'));
    }
}
```

This may be useful in some scenarios, such as testing the title of a page.  But the second translation method will probably be more useful.

### Via Placeholders

Placeholders allow you to injection translatable content within individual strings, such as Xpath.

```
use Magium\AbstractTestCase;

class TranslatorTest extends AbstractTestCase
{
    public function testTranslator()
    {
        $translator = $this->getTranslator();
        $element = $this->webdriver->byXpath(
            $translator->translatePlaceholders('//a[@title="{{Home Page}}"]')
        );

    }
}
```

This is much more useful because it allows you to re-use selectors across different languages.  Oftentimes that is actually what you want: you want the store to behave the same across different languages.

## Translation Configuration

Of course translation is useless without actually providing a translation configuration.  Otherwise how would Magium know how to translate it?

The translation adapter will support any translation file that the Zend Framework translation adapter uses.  Unfortunately ZF2 does not support the translation files for Magento 1.  And so there is some (very easy) configuration that needs to be done.

 M1 uses CSV based translation files such as this:

 ```
"Account","Benutzerkonto"
"My Account","Mein Benutzerkonto"
 ```

 Configuring the adapter requires 1) the absolutely path to the translation file, and 2) the locale that the translation file is referring to.

 ```
 use Magium\AbstractTestCase;

 class TranslatorTest extends AbstractTestCase
 {
     public function testTranslator()
     {
         $translator = $this->getTranslator();
         $translator->addTranslationCsvFile(
            __DIR__ . '/translation.csv',
            'en_US'
         );
         $translator->addTranslationCsvFile(
            __DIR__ . '/translation.de_DE.csv',
            'de_DE'
         );
         $translator->setLocale('de_DE');
         $translated = $translator->translatePlaceholders('{{Account}}');
         self::assertEquals('Benutzerkonto', $translated);

     }
 }
 ```

 ## Themes

 This is some of the coolest magic of Magium.  When you take the combination of translators and themes you can build test elements that will work on almost any i18n site.  Take a look at the configuration for the Magento 1.9 theme for navigating to the account home page.

 ```
 protected $navigateToCustomerPageInstructions            = [
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@class="account-cart-wrapper"]/descendant::span[.="{{Account}}"]'],
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@id="header-account"]/descendant::a[@title="{{My Account}}"]']
    ];
 ```

 Notice the placeholders in there?  Because of that the following code works on every single language pack for Magento (provided that you have configured Magium, of course).  Copy the files from app/locale/*/*.csv into your test directory and add them during the setup.

```
 use Magium\AbstractTestCase;

 class TranslatorTest extends AbstractTestCase
 {
    protected function setUp()
    {
        $translator = $this->getTranslator();
        $translator->addTranslationCsvFile(__DIR__ . '/translation.csv', 'en_US');
        $translator->addTranslationCsvFile(__DIR__ . '/translation.de_DE.csv', 'de_DE');

    }

     public function testTranslator()
     {

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(AccountHome::NAVIGATOR)->navigateTo();

        $this->getNavigator(Switcher::NAVIGATOR)->switchTo('german');
        $translator->setLocale('de_DE');
        $this->getNavigator(AccountHome::NAVIGATOR)->navigateTo();
     }
 }
 ```
