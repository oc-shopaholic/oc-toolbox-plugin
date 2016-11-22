# Trait CodeField
 
 You can use trait in your models with "code" field (string)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-code": "1.0.*"

```

# Usage

```php
    
    class MyModel extend Model {
    
        use Kharanenka\Scope\CodeField;
    
        ...
    
    }
    
    $obElement = MyModel::getByCode('Andrey')->first();
    $obElement = MyModel::likeByCode('And')->first();
    $obElement = MyModel::nullCode()->get();
    $obElement = MyModel::notNullCode()->get();
    
```