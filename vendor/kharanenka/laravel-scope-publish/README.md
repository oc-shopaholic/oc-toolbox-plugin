# Trait PublishField
 
 You can use trait in your models with "publish" field (bool)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/laravel-scope-publish": "1.0.*"

```

# Usage

```php

    
    class MyModel extend Model {
    
        use Kharanenka\Scope\PublishField;
    
        ...
    
    }
    
    $obElement = MyModel::published()->get();
    $obElement = MyModel::getPublished()->get();
    $obElement = MyModel::getByPublishedStart('2016-06-02', '>=')->get();
    $obElement = MyModel::getByPublishedStop('2016-06-02', '<')->get();
    
```