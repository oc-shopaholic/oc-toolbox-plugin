# Trait ModerationField
 
 You can use trait in your models with "moderation" field (bool)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-moderation": "1.0.*"

```

# Usage

```php

    
    class MyModel extend Model {
    
        use Kharanenka\Scope\ModerationField;
    
        ...
    
    }
    
    $obElement = MyModel::moderation()->first();
    $obElement = MyModel::notModeration()->first();
    
```