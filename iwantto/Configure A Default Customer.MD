# I Want To Configure A Default Customer

*Check out [Set My Default Customer Email Or Password](Set My Default Customer Email Or Password.MD) if all you are looking to do is change the email address,  password or basic information.*

In this scenario we are going to look at creating a Customer class that extends the main one.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;

abstract class MyAbstractTestCase extends AbstractMagentoTestCase
{
    public function setUp()
    {
        $this->setTypePreference('Magium\Magento\Identities\Customer', 'Examples\Identities\Customer');

    }
}

class Customer extends \Magium\Magento\Identities\Customer
{

    public function doSomethingAwesome()
    {
        // You can use your imagination
    }

}
```

Unlike a normal Dependency Injection Container which will only honor type preferences during dependency resolution the Magium implementation will return preferred classes.  This is due to the different use case that this implementation is.

In other words if you call `$this->getIdentity()` you want the configured identity and the `AbstractTestCase` will provide it.