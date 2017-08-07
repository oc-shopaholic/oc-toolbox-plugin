# Trait CategoryBelongsTo
 
 You can use trait in your models with "category_id" field (int)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-category-belongs-to": "1.0.*"

```

# Usage

```php
    
    class MyModel extend Model {
    
        use Kharanenka\Scope\CategoryBelongsTo;
    
        ...
    
    }
    
    $obElement = MyModel::getByCategory(10)->first();
    
```