<?php return [
    'plugin' => [
        'name' => 'Toolbox',
        'description' => '',
    ],
    'field' => [
        'id'                                => 'ID',
        'name'                              => 'Название',
        'title'                             => 'Заголовок',
        'active'                            => 'Активность',
        'code'                              => 'Код',
        'slug'                              => 'URL',
        'external_id'                       => 'Внешний ID',
        'preview_text'                      => 'Краткое описание',
        'preview_image'                     => 'Изображение-превью',
        'image'                             => 'Изображение',
        'images'                            => 'Изображения (галлерея)',
        'description'                       => 'Описание',
        'category'                          => 'Категория',

        'sort_order'                        => 'Сортировка',
        'created_at'                        => 'Создано',
        'updated_at'                        => 'Обновлено',
        'deleted_at'                        => 'Удалено',
        'deleted'                           => 'Удаленные',
        'empty'                             => 'Не выбрано',
        'password'                          => 'Пароль',
    ],
    'menu' => [],
    'validation' => [
        "accepted"         => 'The ":attribute" must be accepted.',
        "active_url"       => 'The ":attribute" is not a valid URL.',
        "after"            => 'The ":attribute" must be a date after :date.',
        "alpha"            => 'The ":attribute" may only contain letters.',
        "alpha_dash"       => 'The ":attribute" may only contain letters, numbers, and dashes.',
        "alpha_num"        => 'The ":attribute" may only contain letters and numbers.',
        "array"            => 'The ":attribute" must be an array.',
        "before"           => 'The ":attribute" must be a date before :date.',
        "between"          => [
            "numeric" => 'The ":attribute" must be between :min - :max.',
            "file"    => 'The ":attribute" must be between :min - :max kilobytes.',
            "string"  => 'The ":attribute" must be between :min - :max characters.',
            "array"   => 'The ":attribute" must have between :min - :max items.',
        ],
        "confirmed"        => 'The ":attribute" confirmation does not match.',
        "date"             => 'The ":attribute" is not a valid date.',
        "date_format"      => 'The ":attribute" does not match the format :format.',
        "different"        => 'The ":attribute" and :other must be different.',
        "digits"           => 'The ":attribute" must be :digits digits.',
        "digits_between"   => 'The ":attribute" must be between :min and :max digits.',
        "email"            => 'The ":attribute" format is invalid.',
        "exists"           => 'The selected ":attribute" is invalid.',
        "image"            => 'The ":attribute" must be an image.',
        "in"               => 'The selected ":attribute" is invalid.',
        "integer"          => 'The ":attribute" must be an integer.',
        "ip"               => 'The ":attribute" must be a valid IP address.',
        "max"              => [
            "numeric" => 'The ":attribute" may not be greater than :max.',
            "file"    => 'The ":attribute" may not be greater than :max kilobytes.',
            "string"  => 'The ":attribute" may not be greater than :max characters.',
            "array"   => 'The ":attribute" may not have more than :max items.',
        ],
        "mimes"            => 'The ":attribute" must be a file of type: :values.',
        "extensions"       => 'The ":attribute" must have an extension of: :values.',
        "min"              => [
            "numeric" => 'The ":attribute" must be at least :min.',
            "file"    => 'The ":attribute" must be at least :min kilobytes.',
            "string"  => 'The ":attribute" must be at least :min characters.',
            "array"   => 'The ":attribute" must have at least :min items.',
        ],
        "not_in"           => 'The selected ":attribute" is invalid.',
        "numeric"          => 'The ":attribute" must be a number.',
        "regex"            => 'The ":attribute" format is invalid.',
        "required"         => 'The ":attribute" field is required.',
        "required_if"      => 'The ":attribute" field is required when :other is :value.',
        "required_with"    => 'The ":attribute" field is required when :values is present.',
        "required_without" => 'The ":attribute" field is required when :values is not present.',
        "same"             => 'The ":attribute" and :other must match.',
        "size"             => [
            "numeric" => 'The ":attribute" must be :size.',
            "file"    => 'The ":attribute" must be :size kilobytes.',
            "string"  => 'The ":attribute" must be :size characters.',
            "array"   => 'The ":attribute" must contain :size items.',
        ],
        "unique"           => 'The ":attribute" has already been taken.',
        "url"              => 'The ":attribute" format is invalid.',
    ],
    'tab' => [
        'preview_content'       => 'Превью-контент',
        'full_content'          => 'Полный контент',
        'images'                => 'Изображения',
        'settings'              => 'Настройки',
        'description'           => 'Описание',
    ],
    'component' => [
        'property_name_error_404' => 'View 404 page',
        'property_description_error_404' => '',
        'property_value_on' => 'Yes',
        'property_value_off' => 'No',
        'property_slug' => 'Slug',
    ],
    'button' => [
        'restore' => 'Восстановить',
    ],
    'message' => [
        'create_success'                    => 'Создание :name было успешно выполнено',
        'update_success'                    => 'Редактирование :name было успешно выполнено',
        'delete_success'                    => 'Удаление :name было успешно выполнено',
        'restore_confirm'                   => 'Вы действительно хотите восстановить выбранные элементы?',
        'restore_success'                   => 'Элементы восстановлены',
    ],
    'settings' => [
        'count_per_page' => 'Количество элементов на странице',
        'number_validation' => 'Необходимо ввести число',
        'pagination_limit' => 'Максимальное количество кнопок пагинации',
        'active_class' => 'Класс активной кнопки',
        'button_list' => 'Список кнопок',
        'button_list_description' => 'main,first,first-more,prev,prev-more,next,next-more,last,last-more',
        'button_name' => 'Название кнопки',
        'button_limit' => 'Отображить после страницы',
        'button_number' => 'Отображить имя кнопки как число',
        'button_class' => 'CSS класс',
        'last_button' => '"Последняя"',
        'last-more_button' => '"Еще" (перед "Последняя")',
        'next_button' => '"Следующая"',
        'next-more_button' => '"Еще" (перед "Следующая")',
        'prev_button' => '"Предыдущая"',
        'prev-more_button' => '"Еще" (после "Предыдущая")',
        'first_button' => '"Первая"',
        'first-more_button' => '"Еще" (после "Первая")',
        'main_button' => '"Основная"',
    ],
];