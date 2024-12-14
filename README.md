# bit-magic-bundle
Toolkit to manipulate bitfields in code and symfony forms


# Using the bundle

## Installation
Run 
```shell
composer require stringkey/bit-magic-bundle
```

## Creating a Symfony 6.4 demo app

Create a new Symfony 6.4 LTS project
```shell
symfony new bit-bundle-test --version=lts
````

Include the minimum required bundles
```shell
composer require maker orm form validator twig-bundle security-csrf
```

Setup the database connection string, since the bundle works with standard integer types there should be no issues with any database
create a .env.local file 
```shell
cp .env .env.local
```

And create the database
```shell
php bin/console doctrine:database:create
```

Create an entity
```shell
php bin/console make:entity BitmaskTest
```

Add 2 integer properties and name them
- enableMask
- valueMask

After creating the entity, generate the migration and execute it
```shell
php bin/console doc:mig:diff
php bin/console doc:mig:mig
php bin/console make:crud BitmaskTest
```

Run the symfony development server 
```
symfony serve
```
 
And navigate to http://localhost:8000/bitmask/test 

When clicking new a form with 2 fields are shown, modify the code in:
src/Form/BitmaskTestType.php

Don't forget to include the usages
```php
use Stringkey\BitMagicBundle\Form\BitMaskType;
use Stringkey\BitMagicBundle\Utilities\BitOperations;
```

Modify the build form method
```php
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = BitOperations::createOptions(16, 0xffff); // creates the choice fields

        /** @var BitmaskTest $bitmaskTest */
        $bitmaskTest = $builder->getData(); // fetch the entity
        $enableOption = ['enable_mask' => $bitmaskTest->getEnableMask(), 'choices' => $choices];

        $builder->add('enableMask', BitMaskType::class, ['choices' => $choices]);
        $builder->add('valueMask', BitMaskType::class, $enableOption);
    }
```

Refresh the create page
the form should now show 2 rows of checkboxes the top one controls which fields in the bottom row are enabled
