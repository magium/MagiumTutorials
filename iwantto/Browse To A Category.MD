# I Want To Browse To A Category

There are two ways of browsing to a category

## Automated

The first way is to use the automated way.  Magium depends a lot on automation, and this is the preferred way of doing things for *most* scenarios.  Obviously there may be scenarios where you intend to test something particular.  But for the most part try and depend on automation.

The Magium theme has a default category that you can navigate to.  By default it matches the sample data for that version of Magento.  For example, the theme for Magento CE 1.9 has a default category of "Accessories/Jewelry".

That is done by asking the Theme for the navigation path.

```
class ProductNavigationTest extends AbstractMagentoTestCase
{


    public function testNavigateToJewelry()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getNavigator()->navigateTo($theme->getNavigationPathToProductCategory());
    }
}
```

## Manually

Manually navigating to an arbitrary menu is pretty much exactly the same, except that you don't ask the theme for the path.  Menu hierarchy is delineated with a forward slash (/).
```
class ProductNavigationTest extends AbstractMagentoTestCase
{


    public function testNavigateToJewelry()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getNavigator()->navigateTo('{{Foo}}/{{Bar}}');
    }
}
```

*Wait a sec!*  What are those {{ doing there?  Firstly, they are optional.  Secondly, virtually all information passed to a theme is also passed through a Translator.  And while you are not using the theme directly in this example, the navigator is.  It will split apart the menu selector based off of the forward slash and will pass each menu item to the theme to get the appropriate Xpath selector for the item.  When the selector is passed to the theme it will interpolate a translated string, or the original value if no translation is available.

## Creating your own

Perhaps you have a unique navigation scheme that doesn't work with the default Xpath.  If so you can use the theme's `AbstractConfigurableElement` functionality to implement a [custom Instruction Navigator](Customize How I Browse To A Category.MD).