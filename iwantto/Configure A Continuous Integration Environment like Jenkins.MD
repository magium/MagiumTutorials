# I Want To Configure A Continuous Integration Environment like Jenkins

This item is going to be somewhat incomplete because there are a lot of different variables.

If you have a public facing test site then you can very easily use something like Travis to do CI.

As part of our own CI build there are four items to know

1) Checkout out your source from source control
2) Set (optional) environment variables, such as setting the base URL
3) Run composer update
4) Run PHPUnit

This is our setup right noe

* **Environment Variables**
MAGIUM_MAGIUM_MAGENTO_THEMES_MAGENTO19_THEMECONFIGURATION_baseUrl=http://magento19.loc/
MAGIUM_MAGIUM_MAGENTO_THEMES_MAGENTO18_THEMECONFIGURATION_baseUrl=http://magento18.loc/
MAGIUM_MAGIUM_MAGENTO_THEMES_ADMIN_THEMECONFIGURATION_baseUrl=http://magento19.loc/admin/
* **Composer Update**
php56 /opt/remi/php56/root/etc/composer.phar update
* **Run PHPUnit**
php56 vendor/phpunit/phpunit/phpunit --log-junit results/phpunit/phpunit.xml -c tests/phpunit.xml.dist

Hopefully this helps a little.