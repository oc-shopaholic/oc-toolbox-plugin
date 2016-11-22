# Trait NameField
 
 You can use trait in your models with "name" field (string)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-active": "1.0.*"

```

# Usage

```php

    
    class MyModel extend Model {
    
        use Kharanenka\Scope\NameField;
    
        ...
    
    }
    
    $obElement = MyModel::getByName('Andrey')->first();
    $obElement = MyModel::likeByName('And')->first();
    $obElement = MyModel::nullName()->get();
    $obElement = MyModel::notNullName()->get();
    
```