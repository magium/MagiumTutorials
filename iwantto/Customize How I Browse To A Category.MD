# I Want To Customize How I Browse To A Category

Customizing category navigation can be done on two different places, depending on what you're doing, possibly doing both.  But both methods use themes.  It is just a matter of which theme option you change.

## Changing the category

This is done by modifying the theme class that you are using through the use of configuration.  This is done in the file `/configuration/Magium/Magento/Themes/Magento19/ThemeConfiguration.php`.

```
<?php
$this->navigationPathToProductCategory = '{{Men}}/{{Shirts}}';
```

This is if you want to change the category that is used by the default add to cart functionality because you are testing your own site that uses the core navigation functionality, but just has different categories.

## Changing the browsing mechanism itself

If you have significantly changed the HTML for the category navigation you may need to customize the Xpath that is used for selecting the items.

There are two components you will need to configure to make this work

1) Base Xpath
2) Recursive Child Xpath

Both are required to make this work.  Say you have nodes that are 3 levels deep, the Xpath activity will look like this

1) Move To //baseXpath/descendant::child-level0
2) Move To //baseXpath/descendant::child-level1
3) Move To //baseXpath/descendant::child-level2
4) Click At Current Mouse Location

The two properties are

1) navigationBaseXPathSelector - The base Xpath selector
2) navigationChildXPathSelector - the level-aware child Xpath selector

Let's look at the declaration for the child node getter in the abstract theme configuration class with the selector Xpath.

```
    protected $navigationChildXPathSelector = 'li[contains(concat(" ",normalize-space(@class)," ")," level%d ")]/a[.="%s"]/..';

    public function getNavigationChildXPathSelector($level, $text)
    {
        $return = sprintf($this->navigationChildXPathSelector, $level, $text);
        return $this->translatePlaceholders($return);
    }
```

This code shows us that we need the text (which will be translated via placeholders) as well as the nesting level of the navigation.  This is handled in the `Magium\Magento\Navigators\BaseMenu` class.

Make any customizations you need in the `/configuration/Magium/Magento/Themes/Magento19/ThemeConfiguration.php` file.