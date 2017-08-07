# Trait DateField
 
 You can use trait in your models with date field (Carbon)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-date": "1.0.*"

```

# Usage

```php
    
    class MyModel extend Model {
    
        use Kharanenka\Scope\DateField;
    
        ...
    
    }
    
    $obElement = MyModel::getByDateValue('created_at', '2016-02-06', '>=')->first();
    $sDate = $obElement->getDateValue('created_at', 'd.m.Y');
    
```