# I Want To Configure A Default Theme

*Note - this is not referring to making simple changes to themes.  This is if you need to completely extend the theming system*

Let's start out with how you can change the theme before we get into the requirements for building one.

MagiumMagento starts out *presuming* that Magento CE 1.9 is what you are building to.  If you are building for Magento CE 1.8, for example, you should create a new abstract test case which all of your tests will extend that sets itself up to use the 1.8 theme file.

```

namespace MyTests;

abstract class MyAbstractTestCase extends \Magium\Magento\AbstractMagentoTestCase
{

    protected function setUp()
    {
        parent::setUp();
        $this->switchThemeConfiguration(
            'Magium\Magento\Themes\Magento18\ThemeConfiguration'
        );
    }

}

```

If you can leave it at that and use configuration to change the theme Xpaths then you should go with that approach.

## Creating Custom Themes

Read the previous paragraph.

OK, now that you are thoroughly ready let's start.

The basic method for providing a theme to a class is by having the class define `Magium\Themes\ThemeConfigurationInterface` as a dependency.  The sole purpose of this interface is to provide a global mechanism to get a theme into a class (look at [`AbstractMagentoTestCase::switchThemeConfiguration()`](https://github.com/magium/MagiumMagento/blob/master/lib/Magento/AbstractMagentoTestCase.php)).

For MagiumMagento the primary theme class is `Magium\Magento\Themes\AbstractThemeConfiguration`.  It defines the basic requirements to test Magento and the basic functionality to generate necessary Xpath, but it does not provide that information.

Providing the information is the responsibility of one of the extending classes, such as `Magium\Magento\Themes\Magento19\ThemeConfiguration`.  *That* class will define the Xpaths and configuration information (such as the base URL) used for the theme as a whole.  Be aware that these classes are VERY data-heavy.

## Sub Theme Classes

But the theme configuration classes do not contain all of the Xpaths needed for the test actions to use.  The primary theme class also needs to provide a Customer theme class and a One Page Checkout theme class.  This data is separated to

1) Keep the main theme class under some level of control for size

Well, really, that's it.

It is up to the Theme class to declare the sub-themes by overriding two methods that declare its sub themes.

```
abstract class AbstractThemeConfiguration extends AbstractConfigurableElement implements NavigableThemeInterface
{
    abstract public function getCustomerThemeClass();
    abstract public function getOnePageCheckoutThemeClass();
}
```

Note that `AbstractThemeConfiguration` extends `AbstractConfigurableElement`.  Obviously that means that you can use /configuration (which we have already stated) but it also tells you some other things.  Take a look at the constructor for `AbstractConfigurableElement`.  It requires a translator object.  That means that translation is available in the theme.  What *that* means is that you can re-use the same theme configuration for different, multi-lingual, stores.

## Patterns

There is a lot of information that could be provided here but let's wrap up with some patterns.

### Protected properties

Properties should all be declared protected so they are not accessible from the outside world.  They should be accessed via getters.  Why?  (See next section)

### Complete Xpath

Themes should return *full Xpath*.  What that means is that if a selector has a dependency, such as a product or link name, it needs to declare that dependency via the method signature.

For example, if you are looking to find an element for a certain product name you should provide it to the theme class so that the theme class can provide *you* with the value.  You should not have to guess.

To see what that looks like consider this:

```
    protected $categorySpecificProductPageXpath             = '//h2[@class="product-name"]/descendant::a[.="%s"]';

     public function getCategorySpecificProductPageXpath($productName)
    {
        $xpath = sprintf($this->categorySpecificProductPageXpath, $productName);
        return $this->translatePlaceholders($xpath);
    }
```

The Xpath you get from calling `getCategorySpecificProductPageXpath()` is all you need to call `WebDriver::byXpath()`.

### Xpath Only

Xpath isn't necessarily the best tool for the job in each circumstance compared to text, ID or CSS Selectors.  However, it is the only one that can consistently do the job.  So, for the sake of consistency Xpath should only be used in the theme configuration classes.

... with some exceptions.  Some exceptions would be default data, such as the default category.  That requires multiple instructions to get to and so its text is what is used.  Another example is if there is Regex required for additional processing.  Thankfully there are very few places that require this but it is still sometimes necessary.

 ### Append The Function Name with the Type of Selector

 Again, Xpath should be used almost always, thus leaving you with methods like `getFooterHtmlXpath()`.  But if you are using Regex you should append the method name with Regex.  If you are using Id, Id.  And so forth.  But, again, for the sake of consistency, use Xpath.