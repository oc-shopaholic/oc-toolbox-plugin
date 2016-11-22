# Trait ExternalIDField
 
 You can use trait in your models with "external_id" field (int)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-external-id": "1.0.*"

```

# Usage

```php

    
    class MyModel extend Model {
    
        use Kharanenka\Scope\ExternalIDField;
    
        ...
    
    }
    
    $obElement = MyModel::getByExternalID(16)->first();
    $obElement = MyModel::nullExternalID()->first();
    $obElement = MyModel::notNullExternalID()->first();
    
```