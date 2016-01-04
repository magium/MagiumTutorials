# I Want To Have Different Settings For Individual Developers

There are two ways of determining different settings (such as the theme base URL or a different identies) for individual developers.  Any item that extends `AbstractConfigurableElement` (which themes and identities do) can have its properties defined in these two ways.

## The /configuration directory

It is possible to give each developer complete control over their /configuration directory in the base directory.  The default Magium install does not provide this directory, you must create it.  Simply add the /configuration directory to your .gitignore file and let each developer maintain their own directory.

## Environment Variables

The previous method, however, will become very cumbersome if you are making any theme level changes of any significance.  The most typical use case will likely be to have the /configuration directory under source control so you can easily make changes, and, thus, your developers cannot change it on a person-by-person basis.

To handle this scenario a developer can override any defaults or /configuration options through the use of environment variables.

The environment variables can get pretty long.  They start with MAGIUM_ are prepended by the fully qualified class name in upper case followed by the exact spelling over the property to override.

Given the following element

```
namespace MyNamespace;

class MyClass extends \Magium\AbstractConfigurableElement
{

    protected $testProperty = 1;

}

```

the following environment variable would need to be set.

`MAGIUM_MYNAMESPACE_MYCLASS_testProperty=2`

See [Tests\Magium\Elements\AbstractConfigurableElementTest::testPropertyPassedViaEnvironmentVariable()](https://github.com/magium/Magium/blob/master/tests/Elements/AbstractConfigurableElementTest.php) for more information.

This becomes quite important if you decide to use some kind of [Continuous Integration](Configure A Continuous Integration Environment like Jenkins.MD).