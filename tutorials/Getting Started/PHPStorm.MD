Getting Started with PHPStorm
=============================

Getting Magium to work with PHPStorm is pretty easy.

1. Create a new project in PHPStorm
2. Init Composer
3. Add eschrade/magium as a dependency
![Adding Magium as a dependecy](PHPStorm-Settings-1.png)
4. Go to settings

![Going to settings](PHPStorm-Settings-2.png)
5. Set PHPUnit to the include path (defined by the Composer setup)
![Setting the include path](PHPStorm-Settings-3.png)

Once you have done that you should be able to create your own tests and run it from PHPStorm.

![Running the test](PHPStorm-Settings-4.png)