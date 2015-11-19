Identities
==========

Identities are used to represent personalized data for an individual test.  Out of the box there are only two kinds of identities; Customer and Admin.  Most often they will be used to log in to some part of the site, such as the account pages or the admin pages.  But they can also contain other information, such as billing and shipping addresses.

There is a very good likelihood that your identities are not going to be my identities and so most identities are going to need to be [configurable](../Getting Started/Configurable Elements.MD).  Please make sure you have read that document before continuing.

The admin identity is covered in that page so let's look at the customer identity.

Any identity can be retrieved from the DIC from within the test class as follows

```php
$identity = $this->getIdentity();
```

By default the customer identity will be returned.

The customer identity is a little bit of a weird beast.  Just a bit.  As you might have read, items that change should generally be configurable, and the customer is.  You might want some things to be configurable, such as having the default email address go to your GMail account instead of test@example.com.  But there could be other things that you want to be able to change on a test-by-test basis.

Consider a business requirement that states that you cannot ship to California (say, because your mattress contains material known by the State of California... blah blah blah, or your craft brewer is not from a reciprocal state).  You could build out a brand new customer identity for each condition or you could do this:

```php
$this->getIdentity()->setRegionId('California');
```

and run through the [checkout test](../Actions/Magento/Actions/Checkout/readme.MD), while adding an assertion for the failure.