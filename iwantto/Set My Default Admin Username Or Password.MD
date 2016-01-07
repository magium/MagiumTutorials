# I Want To Set My Default Admin Username Or Password

The admin identity is `Magium\Magento\Identities\Admin` and extends `Magium\Magento\Identities\AbstractEntity`.  The abstract class extends `AbstractConfigurableElement` which means that you can use configuration to change the defaults.

```
<?php

$this->username = 'newadminuser';
$this->password = 'NewPassword';
```
Project file: /configuration/Magium/Magento/Identities/Admin.php