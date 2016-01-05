# I Want To Create My Own Navigator

There could be several reasons why you might want to create your own navigator.

* You could be building a module that you want to distribute as a Magium package
* You could have a customization that needs to be tested
* There may be a part of Magium that has not yet been built up, and after cursing our existence for not having built it yet, you decide to build your own.  (and then do a pull request, right?)

There is no dependency requirement for a navigator.  It could technically just be a blank class.

```
class MyUselessNavigator {}
```

That would be pointless.  Generally speaking a navigator will require at least the WebDriver object.  Also, generally speaking, a navigator *should* have a method called `navigateTo()`.  It may have parameters, it may not.  It depends on the functionality.  But this makes it predictable to some extent.

```
use Magium\WebDriver\WebDriver;

class MyUsefulNavigator
{

    protected $webDriver;

    public function __construct(WebDriver $webDriver)
    {
        $this->webDriver = $webDriver;
    }


    public function navigateTo()
    {
        $this->webDriver->byId('some_id')->click();
    }

}
```

Oftentimes a navigator will also require theme information.  *It is best that this is not hardcoded*.  Use an existing theme or create your own that can provide the information you are looking for.

```
use Magium\Magento\Themes\Magento19\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class MyTheme extends ThemeConfiguration
{

    public function getSomeId()
    {
        return 'some_id';
    }

}

class MyUsefulNavigator
{

    protected $webDriver;
    protected $theme;

    public function __construct(
        WebDriver $webDriver,
        MyTheme $theme)
    {
        $this->webDriver = $webDriver;
        $this->theme = $theme;
    }


    public function navigateTo()
    {
        $this->webDriver->byId($this->theme->getSomeId())->click();
    }

}
```


