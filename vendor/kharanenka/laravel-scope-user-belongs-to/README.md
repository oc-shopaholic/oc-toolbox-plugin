# Trait UserBelongsTo
 
 You can use trait in your models with "user_id" field (int)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-user-belongs-to": "1.0.*"

```

# Usage

```php
    
    class MyModel extend Model {
    
        use Kharanenka\Scope\UserBelongsTo;
    
        ...
    
    }
    
    $obElement = MyModel::getByUser(10)->first();
    
```