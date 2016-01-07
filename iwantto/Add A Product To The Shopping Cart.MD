# I Want To Add A Product To The Shopping Cart

*The mechanism for doing this will change, the architecture is not quite chosen yet*

In order to add a product to the cart you (may) need to be on a page that has products on it.  The current add-to-cart functionality will navigate to a default category but this will likely change (and with it break dozens of test).

Adding to your shopping cart is an action and so the object to do this can be found in the Actions namespace.

## Adding a product to the cart from the category page

```
namespace Tests\Magium\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;

class AddItemToCartTest extends AbstractMagentoTestCase
{

    public function testSimpleAddToCartWithDefaults()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $addToCart = $this->getAction(AddItemToCart::ACTION);
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage();
    }
}
```

That code will add the product to the cart directly from the category page.  Obviously, that needs to be a simple product.  Note that no browsing is done.  The add-to-cart mechanism (currently) will navigate to a default category and add the first simple product to the cart.  *This will change*

## Adding a product to the cart from the product page

To add a simple product to the cart from the product page use code like this:

```
namespace Tests\Magium\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;

class AddItemToCartTest extends AbstractMagentoTestCase
{

    public function testSimpleAddToCartWithDefaults()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $addToCart = $this->getAction(AddItemToCart::ACTION);
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleItemToCartFromProductPage();
    }
}
```

## Adding a specific product to the cart from the product page

This is a little more difficult because it will require you to know a little bit of Xpath.  However, it is not all that difficult.  In this example you will need to use the theme to add to the cart and a navigator to get there.

*Note that this does not work quite yet.  The add-to-cart command will trigger a navigation.  This will change*

```
namespace Tests\Magium\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Cart\AddItemToCart;

class AddItemToCartTest extends AbstractMagentoTestCase
{

    public function testSimpleAddToCartWithDefaults()
    {
        $productName = 'Pearl Stud Earrings';
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator()->navigateTo('Accessories/Jewelry');
        $this->getNavigator(Product::NAVIGATOR)->navigateTo($productName);
        $this->assertTitleContains($productName);

        $addToCart = $this->getAction(AddItemToCart::ACTION);
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */
        $addToCart->addSimpleItemToCartFromProductPage();
    }
}
```