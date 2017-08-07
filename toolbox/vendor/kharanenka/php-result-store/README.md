# Class Result

Universal result store:
 - status (bool)
 - data (mixed)
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/php-result-store": "2.0.*"

```

# Usage

You can use class "Result" in any places your application. Class "Result" is singleton.

## Set result data methods:
  - setTrue(mixed $obData = null) - Set result data with status "true"
  - setFalse(mixed $obData = null) - Set result data with status "false"
  - setMessage(string $sMessage) - Set error message with status "false"
  - setCode(string $sCode) - Set error code with status "false"

## Get result data method:
  - status() - Get result status flag true/false
  - data() - Get data value (object/array/string)
  - message() - Get error message value
  - code() - Get error code value
  - get() - Get array result array
  - getJSON() - Get array result array in JSON string
  
```php

    //Result array
    [
        'status'    => false/true
        'data'      => object
        'message'   => 'Error message text',
        'code'      => 1015,
    ]
```

```php
    //Example 1
    Result::setMessage('Error')->setCode(400);
    
    ...
    if(!Result::status()) {
        return Result::get();
    }
    
    //Example 2
    return Result::setTrue($obData)->getJSON();
```