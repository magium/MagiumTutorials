# I Want To Validate Or Verify Order Information In The Admin

Navigating the admin is hard.  Navigating the admin order pages, doubly so.  Extracting information from the admin order page, triply so.

Thankfully there are a combination of navigators and extractors that you can use to extract the information that a CSR can see.  The following test shows a glimpse of how that can be done.

 ```
<?php


use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Login\Login;
use Magium\Magento\Actions\Cart\AddItemToCart;
use Magium\Magento\Actions\Checkout\GuestCheckout;
use Magium\Magento\Extractors\Admin\Order\AccountInformation;
use Magium\Magento\Extractors\Admin\Order\BillingAddress;
use Magium\Magento\Extractors\Admin\Order\OrderItems;
use Magium\Magento\Extractors\Admin\Order\OrderSummary;
use Magium\Magento\Extractors\Admin\Order\PaymentInformation;
use Magium\Magento\Extractors\Admin\Order\Totals;
use Magium\Magento\Extractors\Admin\OrderInformationExtractor;
use Magium\Magento\Extractors\Checkout\OrderId;
use Magium\Magento\Navigators\Admin\Order;

class OrderInformationExtractorTest extends AbstractMagentoTestCase
{


    public function testOrderExtraction()
    {

       // Imagine adding a product to the cart and completing a checkout

        $orderId = $this->getExtractor(OrderId::EXTRACTOR)->getOrderId();
        $this->getAction(Login::ACTION)->login();
        $this->getNavigator(Order::NAVIGATOR)->navigateTo($orderId);

        $extractor = $this->getExtractor(OrderInformationExtractor::EXTRACTOR);
        $extractor->extract();

        $identity = $this->getIdentity();
        /* @var $identity \Magium\Magento\Identities\Customer */

        $billingAddress = $this->getExtractor(BillingAddress::EXTRACTOR);
        /* @var $billingAddress \Magium\Magento\Extractors\Admin\Order\BillingAddress */
        $billingAddress->extract();

        // Assert the identity name matches the order name
        self::assertEquals(
            $identity->getBillingFirstName() . ' ' . $identity->getBillingLastName(),
            $billingAddress->getName()
        );

        // Assert that they are a guest user
        self::assertEquals('NOT LOGGED IN', $accountInformation->getCustomerGroup());

        // Assert the item acount
        self::assertCount(1, $items->getOrderItems());

        foreach ($items->getOrderItems() as $item) {

            /* @var $item \Magium\Magento\Extractors\Admin\Order\OrderItem */

            // Assert the quantities of shipped and ordered products
            self::assertEquals(1, $item->getQtyOrdered());
            self::assertEquals(0, $item->getQtyShipped());
        }

        $totals = $this->getExtractor(Totals::EXTRACTOR);
        /* @var $totals \Magium\Magento\Extractors\Admin\Order\Totals */

        // Assert that they have not paid anything (or calculate the total from the order items
        self::assertEquals('$0.00', $totals->getTotalPaid());

    }

}
 ```