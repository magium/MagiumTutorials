# I Want To Log In As An Admin

The admin login component probably does to much and might be split into two distinct actions.  But for now all you need to do is call the Login action.  It will go to the admin page, check to see if you are logged in, and then log you in if you are not already logged in.

```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Login\Login;

class AdminNavigationTest extends AbstractMagentoTestCase
{

    public function testAdminLogin()
    {
        $this->getAction(Login::ACTION)->login();

    }
}
```

If you want to change admin credentials you can do it in the test.


```
<?php

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Login\Login;

class AdminNavigationTest extends AbstractMagentoTestCase
{

    public function testAdminLogin()
    {
        $admin = $this->getIdentity(Admin::IDENTITY);
        $admin->setUsername('admin');
        $admin->setPassword('Password1');
        $this->getAction(Login::ACTION)->login();

    }
}
```

Or (probably more appropriately) you can set it via configuration

```
<?php

$this->username = 'admin';
$this->password = 'Password1';

```
File: /configuration/Magium/Magento/Identities/Admin.php