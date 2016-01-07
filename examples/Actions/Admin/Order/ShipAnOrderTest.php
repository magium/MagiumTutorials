<?php

namespace Examples\Action\Admin\Order;

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