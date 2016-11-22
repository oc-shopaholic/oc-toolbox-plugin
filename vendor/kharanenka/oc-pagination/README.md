# Class Pagination
 
 You can get pagination elements with "Pagination" class.
 See [php-pagination](https://github.com/kharanenka/php-pagination) package.
 You can copy the lang file from example oc-pagination/src/Kharanenka/lang/en/lang.php
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/oc-pagination": "1.0.*"

```

#Component properties
```php

public function defineProperties()
{
    $arProperties = [
        //Component property array
    ];
    
    $arProperties = array_merge($arProperties, Pagination::getProperties('plugin_name'));
    return $arProperties;
}
```

# Usage
```php

//$arSettings = $this->properties
$arPagination = Pagination::get($iCurrentPage, $iTotalCount, $arSettings);
 
```

#Result
```php

[
    [
        'name' => 'First',
        'value' => 1,
        'class' => 'pagination-first-button',
        'code' => 'first',
    ],
    ...
    [
        'name' => '3',
        'value' => 3,
        'class' => 'pagination-i _act',
        'code' => null,
    ],
    ...
    [
        'name' => 'Last',
        'value' => 10,
        'class' => 'pagination-last-button',
        'code' => 'last',
    ]
]

```