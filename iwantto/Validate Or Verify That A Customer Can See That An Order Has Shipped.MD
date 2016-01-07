# I Want To Validate Or Verify That A Customer Can See That An Order Has Shipped

This is a complicated activity to do since it requires

* Add to cart
* Checkout
* Admin Login
* Admin navigation to the order
* Shipping the order
* Logging in as the customer
* Navigating to the order
* Extracting the order information.
* Asserting the order has been shipped

It's complicated, but it is also probably not too much of a stretch to say that is a legitimate user story.

Let's see how it looks in code:

```
<?php


use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Login\Login;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\CustomerCheckout;
use Magium\Magento\Extractors\Checkout\OrderId;
use Magium\Magento\Extractors\Customer\Order\ItemList;
use Magium\Magento\Navigators\Admin\Order;
use Magium\Magento\Navigators\Customer\AccountHome;
use Magium\Magento\Navigators\Customer\NavigateToOrder;

class ShipAnOrderTest extends AbstractMagentoTestCase
{

    public function testShippingAndOrder()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction(AddItemToCart::ACTION)->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
        $this->getAction(CustomerCheckout::ACTION)->execute();

        $this->getAction(Login::ACTION)->login();
        $this->getNavigator(Order::NAVIGATOR)->navigateTo($this->getExtractor(OrderId::EXTRACTOR)->getOrderId());

        $this->byText('{{Ship}}')->click();
        $this->byText('{{Submit Shipment}}')->click();

        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator(AccountHome::NAVIGATOR)->navigateTo();
        $this->getNavigator(NavigateToOrder::NAVIGATOR)->navigateTo($this->getExtractor(OrderId::EXTRACTOR)->getOrderId());

        $extractor = $this->getExtractor(ItemList::EXTRACTOR);
        /* @var $extractor \Magium\Magento\Extractors\Customer\Order\ItemList */
        $extractor->extract();
        $products = $extractor->getItems();
        self::assertEquals(1, $products[0]->getQtyShipped());
    }

}
```
[(source)](../examples/Actions/Admin/Order/ShipAnOrderTest.php)
Given the complexity of the story, that's not that bad.