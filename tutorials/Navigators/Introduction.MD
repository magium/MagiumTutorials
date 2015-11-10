Navigators
==========

Navigators are exactly what they sound like.  They are objects that are intended to help you navigate through various parts of the website you are testing.  Most of the time the navigation classes will be heavily dependent on theme configuration objects.

The [BaseMenuNavigator](Base Menu Navigator.MD] class is a good example of this.  It provides basic category navigation across the frontend of the web site.  That means that rather than providing a number of Selenium commands to select this and that element you can simply use code like this

```php
$this->getNavigator()->navigateTo('Accessories/Jewelry');
```

and, voila, you're there.

Magium is, internally, highly dependant on Xpath and the Xpath that works on one site may not work on another.  The [theme classes](../Themes/Introduction.MD) can be used to specify working Xpath for individual Magento themes.

Navigators can be fairly simple, such as the [BaseMenuNavigator](Base Menu Navigator.MD), which bases itself on a root Xpath and iterates over the provided paths (Accessories/Jewelry) with a child Xpath statement.  They can also be more complex such as the Customer\Login navigator.  The more complex navigators tend to be when a navigation cannot be accomplished via a pattern and so need instructions to accomplish their goal.  This is done by providing those instructions to an [Instruction Navigator](Instruction Navigator.MD).

The AbstractMagentoTestCase class contains a helper function call getNavigator().  This method will construct a class name and request it from the DIC.  You provide the name of the navigator and it will prepend Magium\Magento\Navigators to the class name, as long as your requested class name does not start with the word "Magium".  That means that the code

```php
$navigator = $this->getNavigator('BaseMenuNavigator');
```

and

```php
$navigator = $this->getNavigator('Magium\Magento\Navigators\BaseMenuNavigator');
```

will do the same thing.
