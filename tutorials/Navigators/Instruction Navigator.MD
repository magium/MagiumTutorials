Instruction Navigator
=====================

The instruction navigator is similar to the [Base Menu Navigator](Base Menu Navigator.MD) in its intent, and some of the underlying functionality.  However, there is a crucial difference in that the base menu navigator uses iteration for predictable elements.  The instruction navigator is for non-iterative navigation instructions.

It is also intended for general functionality that might change based off of your theme. Unlike the [System Configuration Navigator](System Configuration Navigator.MD), which is complicated but prone to stasis, something like logging in to your account is both complex and prone to change.  It involves clicks on non-pattern-able HTML elements and it is heavily reused.  A lot of navigation in Magento is going to be directly scripted via WebDriver when it is not repeatable and not configurable.  But if both of those conditions are true (repeatability and configurability) then that makes an action a candidate for using the instruction navigator.

The instruction set is a multi-dimensional array in the form of

```
[
    [action, xPath],
    [action, xPath]
]
```

Note that the individual instruction arrays are not key => value pairs.

Currently there are two kinds of actions

1. Mouse Move
2. Mouse Click

There is no particular reason other than utility for those being the only actions being available.

The individual actions are the string values `mouseClick`, and `mouseMoveTo`.  They are generally going to be referenced by the static constants in the `Magium\WebDriver\WebDriver` class.  The `ThemeConfiguration` class provides a simple example for navigating to the login page.

```php
 protected $loginInstructions            = [
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@class="account-cart-wrapper"]/descendant::span[.="Account"]'],
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@id="header-account"]/descendant::a[@title="My Account"]']
    ];
 ```

That's about it.