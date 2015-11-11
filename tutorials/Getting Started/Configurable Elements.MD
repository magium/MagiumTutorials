Configurable Elements
=====================

No Magento installation is exactly the same and so there are some elements that will need to be changed to correspond with your situation.  *Processes*, such login or checkout, will probably remain the same, but the components of the test required to execute those tests will likely be different.

Consider the administrator.  It is unlikely that your administrator account will have the same credentials as the default.  And so you will want to customize the identity.

There are two ways of customizing the identity.  The first is to

1. extend the `Magium\Magento\Identities\Admin` class, updating the credentials
2. Set the type preference in the DIC for `Magium\Magento\Identities\Admin` to the name of your new class

or

1. Create a configuration file for the class.

As you might expect, creating a file is probably easier than extending the original class and setting the type preference for the DIC.  So it's best to do that.

In order to configure an individual element you need to

1. Make sure you have a directory called `configuration` in the root of your project (Technically, that's not true, but never mind that).
2. In the `configuration` directory, create a directory tree that matches the namespace of the configurable class
3. In that directory create a file that matches the class name you are configuring
4. Set your properties

Number 4 sounds weird, doesn't it?  The way a configurable element is configured is by `include()`ing the file in step 3 in the constructor.

That's it.

Making It Work
==============

What does this look like in real life?  Let's look at `Magium\Magento\Identites\Admin`.

```php
class Admin extends AbstractEntity
{

    protected $account   = 'admin';
    protected $password  = '123123qa';

    public function getAccount()
    {
        return $this->account;
    }

}
```

Those are the defaults.  If we want to change them we need to create a new directory in the root of our project called `configuration/Magium/Magento/Identities`.  In that directory we need to create a file called `Admin.php`.  And in that file we need to enter something like

```php
$this->account   = 'kschroeder';
$this->password  = 'password';
```

And you're done.  Now if you want to log in to the admin area during a test all you need to do is write the following code.

```php
class AdminMenuNavigatorTest extends AbstractMagentoTestCase
{

    public function testAdminMenuNavigation()
    {
        $this->getAction('Admin\Login\Login')->login();
    }

}
```

What!?  How does that log you in?  The identity isn't specified anywhere!

Ahh, the magic of the Dependency Injection Container.  Yes, I'm aware I'm using it wrongly, but it solves the problem quite nicely.  The reason why this works is because the action that is requested (`Magium\Magento\Actions\Admin\Login\Login`) has the following as its constructor:

```php
    protected $adminIdentity;

    public function __construct(
        AdminThemeConfiguration $theme,
        Admin      $adminIdentity,
        InstructionNavigator    $instructionsNavigator,
        WebDriver               $webdriver,
        AbstractMagentoTestCase        $testCase,
        Open                    $open,
        Messages                $messages
    ) {
        $this->theme         = $theme;
        $this->adminIdentity = $adminIdentity;
        $this->webdriver     = $webdriver;
        $this->testCase      = $testCase;
        $this->openCommand   = $open;
        $this->messages      = $messages;
    }
```

See the second parameter?  That's where the admin identity is requested.  The login action will then use that configured admin identity when it logs in.

