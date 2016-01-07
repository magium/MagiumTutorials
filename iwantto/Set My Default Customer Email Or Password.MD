# I  Want To Set My Default Customer Email Or Password


The customer identity class contains all of the information needed to successfully process a checkout action, not including the payment or shipping methods.

Say you are based out of Florida and you want most of your purchases to come from a Florida address.  The easiest way of changing this is through the use of a configuration file that will override any properties defined in the class.

`Magium\Magento\Identities\Customer` extends `Magium\AbstractConfigurableElement` which means that it will look in the base directory's /configuration folder to find a file that contains alternate information.

 The pattern is `/configuration/Namespace/ChildNamespace/Class.php`.  So for the class `Magium\Magento\Identities\Customer` the name of the file will be `/configuration/Magium\Magento/Identities/Customer.php`.

 That file is `include()`ed during object instantialization.  That a few things.

 1. It is part of the class definition itself.  This then follows that
 2. It can contain PHP code

 ```
 <?php

 // Using this kind of comment helps your IDE with code completion.
 /* @var $this \Magium\Magento\Identities\Customer */

 $this->billingRegionId = 'Florida';

 $this->emailAddress = 'myactualemail@example.com';
 $this->password = 'myactualpassword';
 ```