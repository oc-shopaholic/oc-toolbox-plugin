<?php namespace Kharanenka\Helper;

use Lang;

/**
 * Class Pagination
 * @package Kharanenka\Helper
 * @author Andrey Kharanenka, kharanenka@gmail.com
 */
class Pagination extends PaginationHelper {

    /**
     * Get pagination properties
     * @param string $sPluginName
     * @return array
     */
    public static function getProperties($sPluginName) {
        return [
            'count_per_page' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.count_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['count_per_page'],
            ],
            'pagination_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.pagination_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['pagination_limit'],
            ],
            'active_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.active_class',
                'type'              => 'string',
                'default'           => self::$arSettings['active_class'],
            ],
            'button_list' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_list',
                'description'       => 'lovata.'.$sPluginName.'::lang.settings.button_list_description',
                'type'              => 'string',
            ],

            //First button
            'first_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['first_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.first_button',
            ],
            'first_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['first_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.first_button',
            ],
            'first_button_number' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_number',
                'type'              => 'checkbox',
                'default'           => self::$arSettings['first_button_number'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.first_button',
            ],
            'first_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['first_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.first_button',
            ],

            //First-more button
            'first-more_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['first-more_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.first-more_button',
            ],
            'first-more_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['first-more_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.first-more_button',
            ],
            'first-more_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['first-more_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.first-more_button',
            ],

            //Prev button
            'prev_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['prev_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.prev_button',
            ],
            'prev_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['prev_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.prev_button',
            ],
            'prev_button_number' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_number',
                'type'              => 'checkbox',
                'default'           => self::$arSettings['prev_button_number'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.prev_button',
            ],
            'prev_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['prev_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.prev_button',
            ],

            //Prev-more button
            'prev-more_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['prev-more_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.prev-more_button',
            ],
            'prev-more_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['prev-more_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.prev-more_button',
            ],
            'prev-more_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['prev-more_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.prev-more_button',
            ],

            //Main number buttons
            'main_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['main_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.main_button',
            ],

            //Next-more button
            'next-more_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['next-more_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.next-more_button',
            ],
            'next-more_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['next-more_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.next-more_button',
            ],
            'next-more_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['next-more_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.next-more_button',
            ],

            //Next button
            'next_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['next_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.next_button',
            ],
            'next_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['next_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.next_button',
            ],
            'next_button_number' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_number',
                'type'              => 'checkbox',
                'default'           => self::$arSettings['next_button_number'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.next_button',
            ],
            'next_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['next_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.next_button',
            ],

            //Last-more button
            'last-more_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['last-more_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.last-more_button',
            ],
            'last-more_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['last-more_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.last-more_button',
            ],
            'last-more_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['last-more_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.last-more_button',
            ],

            //Last button
            'last_button_name' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_name',
                'type'              => 'string',
                'default'           => self::$arSettings['last_button_name'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.last_button',
            ],
            'last_button_limit' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_limit',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => Lang::get('lovata.'.$sPluginName.'::lang.settings.number_validation'),
                'default'           => self::$arSettings['last_button_limit'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.last_button',
            ],
            'last_button_number' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_number',
                'type'              => 'checkbox',
                'default'           => self::$arSettings['last_button_number'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.last_button',
            ],
            'last_button_class' => [
                'title'             => 'lovata.'.$sPluginName.'::lang.settings.button_class',
                'type'              => 'string',
                'default'           => self::$arSettings['last_button_class'],
                'group'             => 'lovata.'.$sPluginName.'::lang.settings.last_button',
            ],
        ];
    }

    /**
     * Get pagination elements
     * @param int $iCurrentPage - current page number
     * @param int $iTotalCount - total count elements
     * @param array $arSettings - settings array
     * @return array
     */
    public static function get($iCurrentPage, $iTotalCount, $arSettings = []) {

        if(!empty($arSettings) && isset($arSettings['button_list']) && !empty($arSettings['button_list'])) {
            $arSettings['button_list'] = explode(',', $arSettings['button_list']);
        }


        return parent::get($iCurrentPage, $iTotalCount, $arSettings);
    }
}