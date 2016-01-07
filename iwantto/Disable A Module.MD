# I Want To Disable A Module

Enabling or Disabling a module require the use of the `Enabler`.  It is very easy to use.  Once you have logged in to the Admin UI (using the Admin login action) run the following code.

```
namespace Tests\Magium\Magento\Admin;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Admin\Configuration\Enabler;
use Magium\Magento\Actions\Admin\Login\Login;

class EnableModuleTest extends AbstractMagentoTestCase
{

    public function testDisableSavedCC()
    {
        // Login to the admin
        $enabler = $this->getAction(Enabler::ACTION);
        /** @var $enabler \Magium\Magento\Actions\Admin\Configuration\Enabler */

        $enabler->disable('Payment Methods/Saved CC');
    }
}
```

Once logged in the Enabler will navigate to the system configuration, click on the "Payment Methods" tab, check to see if the Enable label is visible under the "Saved CC" section, toggle it (if necessary), set Enabled to "No" and click Save.