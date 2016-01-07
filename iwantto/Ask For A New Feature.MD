How Do I Ask For A New Feature?
===============================

Very easily.  Go to the [Magium Github issues page](https://github.com/magium/Magium/issues) and create a new issue.  Try to keep it to 1-2 sentences such as *"I would like to be able to toggle the Full Page Cache in the admin UI"*.  There is a lot of functionality in Magento to be automated and so knowing what is most important is important.

Can you create new features and push them to our GitHub repo?  Absolutely!  But if you are not familiar enough with Magium *don't hesitate to ask us to build it*.

If you do build a feature, please remember the following rules:

* Use the SOLID methodology as much as possible
* Provide a constant that provides the class resolution (such as `const ACTION='Namespace\Class';`).  This is very important to help people use their IDE to make Magium easy to use.
* Use `AbstractConfigurableElement` liberally, where appropriate, of course.  This is the easiest way for someone to customize its usage.
* Use theme classes to define default Xpath's and make them extend `AbstractConfigurableElement`.
* Use Xpath by default for customizations intended for the core distro.  (Use IDs and CSS selectors in custom code)
* Talk to us.  We will be helpful (that's a promise)