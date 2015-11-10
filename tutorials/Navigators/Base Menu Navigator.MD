Base Menu Navigator
===================

The BaseMenuNavigator class provides functionality for navigating over simple, hierarchical HTML structures such as a frontend nested menu.  It starts with a base Xpath that is used to note the root of element, usually based off of an HTML ID, followed by iteratively executed compounded child Xpath statements.

To see how this works consider the following HTML

```html
<html>
    <body>
        <ul id="sidebar">
            <li>Item 1</li>
            <li>Item 2</li>
        </ul>
        <ul id="nav">
            <li>Child 1
                <ul>
                    <li>Child 2</li>
                </ul>
            </li>
            <li>Child 3</li>
        </ul>
    </body>
</html>
```
The root Xpath would be `//ul[@id="nav"]`.  The child Xpath would be something like `li[.="%s"]` which would be concatenated onto the root with the "descendant" instruction.  Then you would provide 'Child 1/Child 2' to the navigator and it would execute the following commands

1. Mouse move to element `//ul[@id="nav"]/descendant::li[.="Child 1"]`
2. Mouse move to element `//ul[@id="nav"]/descendant::li[.="Child 1"]/descendant::li[.="Child 2"]`
3. Click on element `//ul[@id="nav"]/descendant::li[.="Child 1"]/descendant::li[.="Child 2"]`

Note that this is a simple example and wouldn't actually work because the text for `//ul[@id="nav"]/descendant::li[.="Child 1"]` actually contains some spaces and so the exact text match will not work.  That's why the theme's child node selector Xpath is the more complicated `li[contains(concat(" ",normalize-space(@class)," ")," level%d ")]/a[.="%s"]/..`.

As noted in other pages, if you can use the base functionality in the `BaseMenuNavigator` the preferred method of customization is not to customize the navigator but to customize the [theme](../Themes/ThemeConfiguration.MD) through the use of the functionality in [AbstractConfigurableElement](../Themes/Configuration.MD).  The customized child Xpath will need to have an `sprint()` place holder for the depth and for the string.  In the Magento 1.9 theme each menu item will have an <a> element with the text held inside a parent element that has the depth specified as a CSS class.  Your customization will need to include the same.  But if you are using the base Magento theme, and reusing the core menu functionality and only changing the skin, no customizations should be necessary.

Using the `BaseMenuNavigator` within the test is quite easy.  The AbstractMagentoTestCase class contains a helper function call getNavigator().  This method will construct a class name and request it from the DIC.
