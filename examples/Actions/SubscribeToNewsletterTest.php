<?php

namespace Examples\Actions;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\WebDriver\WebDriver;

class SubscribeToNewsletterTest extends AbstractMagentoTestCase
{

    public function testSubscribeToNewsletter()
    {
        self::addBaseNamespace('Examples');
        $this->switchThemeConfiguration('Examples\Actions\ThemeConfiguration');
        $this->commandOpen($this->getTheme()->getBaseUrl());

        $identity = $this->getIdentity();
        /* @var $identity Customer */
        $identity->generateUniqueEmailAddress();

        $action = $this->getAction(SubscribeToNewsletter::ACTION);
        $action->subscribe($identity->getEmailAddress());

    }

}

class SubscribeToNewsletter
{

    const ACTION = 'SubscribeToNewsletter';

    protected $theme;
    protected $webDriver;
    protected $testCase;

    public function __construct(
        WebDriver $webDriver,
        ThemeConfiguration $themeConfiguration,
        AbstractMagentoTestCase $testCase
    )
    {
        $this->webDriver = $webDriver;
        $this->theme = $themeConfiguration;
        $this->testCase = $testCase;
    }

    public function subscribe($emailAddress)
    {
        $this->testCase->assertElementDisplayed($this->theme->getNewsletterEmailId());
        $this->testCase->assertElementDisplayed($this->theme->getNewsletterSubscribeXpath(), WebDriver::BY_XPATH);

        $emailElement = $this->webDriver->byId($this->theme->getNewsletterEmailId());
        $emailElement->clear();
        $emailElement->sendKeys($emailAddress);

        $subscribeElement = $this->webDriver->byXpath($this->theme->getNewsletterSubscribeXpath());
        $subscribeElement->click();

        $this->testCase->assertElementExists(
            $this->theme->getNewsletterSubscribeSucceededXpath(),
            WebDriver::BY_XPATH
        );
    }
}

class ThemeConfiguration extends \Magium\Magento\Themes\Magento19\ThemeConfiguration
{

    protected $newsletterEmailId = 'newsletter';

    protected $newsletterSubscribeXpath = '//button[@title="{{Subscribe}}"]';

    protected $newsletterSubscribeSucceededXpath = '//li[@class="success-msg"]/descendant::span[.="{{Thank you for your subscription.}}"]';

    /**
     * @return string
     */
    public function getNewsletterEmailId()
    {
        return $this->newsletterEmailId;
    }

    /**
     * @return string
     */
    public function getNewsletterSubscribeXpath()
    {
        return $this->translatePlaceholders($this->newsletterSubscribeXpath);
    }

    /**
     * @return string
     */
    public function getNewsletterSubscribeSucceededXpath()
    {
        return $this->translatePlaceholders($this->newsletterSubscribeSucceededXpath);
    }

}