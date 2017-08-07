# Class PaginationHelper
 
 You can get pagination elements with "PaginationHelper" class
 
#Installation
Require this package in your `composer.json` and update composer.
 
```php

"kharanenka/php-pagination": "2.0.*"

```

# Usage
```php

 $arPagination = PaginationHelper::get($iCurrentPage, $iTotalCount, $arSettings);
 
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

#Default settings
```php

$arSettings = [

        //Common settings
        'button_list' => ['main'],                  // Button list
                                                    // You can set order button in 'button_list' array
                                                    // Button key list:
                                                    // - main
                                                    // - first
                                                    // - first-more
                                                    // - prev
                                                    // - prev-more
                                                    // - next
                                                    // - next-more
                                                    // - last
                                                    // - last-more
                                                    // For example:
                                                    // ['prev', 'main', 'next']
                                                    // ['first', 'prev', 'main', 'next', 'last']
                                                    // ['prev', 'first', 'main', 'last', 'next']
        
        'count_per_page' => 10,                     // Count elements per page
        'pagination_limit' => 5,                    // Count main pagination buttons
        'active_class' => '_act',                   // Button class for active page 

        //Button "First"
        'first_button_name' => 'First',             // Button name
        'first_button_limit' => 1,                  // Show button if current page > this value
        'first_button_number' => false,             // true - button name = page number
        'first_button_class' => null,               // Button class

        //Button "First-More"
        'first-more_button_name' => '...',          // Button name
        'first-more_button_limit' => 1,             // Show button if current page > this value
        'first-more_button_class' => null,          // Button class

        //Button "Prev"
        'prev_button_name' => 'Prev',               // Button name
        'prev_button_limit' => 1,                   // Show button if current page > this value
        'prev_button_number' => false,              // true - button name = page number
        'prev_button_class' => null,                // Button class

        //Button "Prev-More"
        'prev-more_button_name' => '...',           // Button name
        'prev-more_button_limit' => 1,              // Show button if current page > this value
        'prev-more_button_class' => null,           // Button class

        //Main buttons
        'main_button_on' => true,                   // Switch on/off button
        'main_button_class' => null,                // Button class

        //Button "Next-More"
        'next-more_button_name' => '...',           // Button name
        'next-more_button_limit' => 1,              // Show button if current page + this value <= total page count
        'next-more_button_class' => null,           // Button class

        //Button "Next"
        'next_button_name' => 'Next',               // Button name
        'next_button_limit' => 1,                   // Show button if current page + this value <= total page count
        'next_button_number' => false,              // true - button name = page number
        'next_button_class' => null,                // Button class

        //Button "Last-More"
        'last-more_button_name' => '...',           // Button name
        'last-more_button_limit' => 1,              // Show button if current page + this value <= total page count
        'last-more_button_class' => null,           // Button class

        //Button "Last"
        'last_button_name' => 'Last',               // Button name
        'last_button_limit' => 1,                   // Show button if current page + this value <= total page count
        'last_button_number' => false,              // true - button name = page number
        'last_button_class' => null,                // Button class
    ];

```