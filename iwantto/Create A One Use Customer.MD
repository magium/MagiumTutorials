# I Want To Create A One Use Customer

There might be some cases where you might want to create a one-off customer.  Perhaps there is some kind of validation that needs to occur or there is a unique scenario that occurs on registration like a specific coupon code that is given to new users.

 To do that there are two items we need to utilize:

 1) The Customer Identity
 2) The Registration Action.

 ```
 <?php

 use Magium\Magento\AbstractMagentoTestCase;
 use Magium\Magento\Actions\Customer\Logout;
 use Magium\Magento\Actions\Customer\Register;

 class RegisterCustomerTest extends AbstractMagentoTestCase
 {

     public function testRegistration()
     {
         $this->commandOpen($this->getTheme()->getBaseUrl());

         $this->getIdentity()->generateUniqueEmailAddress();

         $this->getAction(Register::ACTION)->register();

     }

 }
 ```