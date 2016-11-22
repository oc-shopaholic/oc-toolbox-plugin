# Trait SlugField
 
 You can use trait in your models with "slug" field (string)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-slug": "1.0.*"

```

# Usage

```php

    
    class MyModel extend Model {
    
        use Kharanenka\Scope\SlugField;
    
        ...
    
    }
    
    $obElement = MyModel::getBySlug('andrey')->first();
    $obElement = MyModel::nullSlug()->get();
    $obElement = MyModel::notNullSlug()->get();
    
```