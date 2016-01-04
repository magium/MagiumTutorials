# I Want To Go To My Order As A Customer

You might want to go to an order as a customer to cancel the order, check the status or one of a hundred thousand other things.

Navigating to an order is done using the Customer order navigator.

```
<?php


use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\CustomerCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;
use Magium\Magento\Navigators\Customer\AccountHome;
use\Magium\Magento\Navigators\Customer\NavigateToOrder;

class NavigateToOrderTest extends AbstractMagentoTestCase
{

    public function testCreateAndNavigateToOrder()
    {

        // Imagine adding a product to the cart and checking out

        $orderId = $this->getExtractor(OrderId::EXTRACTOR)->getOrderId();

        // Navigate to the account home page, logging in if necessary
        $this->getNavigator(AccountHome::NAVIGATOR)->navigateTo();
        $this->getNavigator(NavigateToOrder::NAVIGATOR)->navigateTo($orderId);

    }

}
```