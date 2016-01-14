
## Create My Own Abstract Class

This is not a requirement.  However you will probably find that after a while little customizations that you make start to add up and it starts getting repetitive.  If things start getting repetitive your productivity will drop.  It's easy to create your own *so just do it*.

 Create a `lib` folder and call it `MyAbstractTestCase` or whatever you want.  Create a setUp() method, making sure to call `parent::setUp()`.  Then set any customizations *after* the call to `parent::setUp()`.

```

 namespace MyTests;

 abstract class MyAbstractTestCase extends \Magium\Magento\AbstractMagentoTestCase
 {

    protected function setUp()
    {
        parent::setUp();
        // Do your own configuration such as setting your theme configuration
    }

 }


```

 Now when you write a test you will extend this new abstract class instead of `AbstractMagentoTestCase`.

```

 namespace MyTests;

 class LoginTest extends MyAbstractTestCase
 {

    public function testLogin()
    {
        // imagine test here
    }

 }

```