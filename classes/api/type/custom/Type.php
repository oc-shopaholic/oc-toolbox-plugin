<?php namespace Lovata\Toolbox\Classes\Api\Type\Custom;

use GraphQL\Type\Definition\ScalarType;

/**
 * Class Type
 * @package Lovata\Toolbox\Classes\Api\Type\Custom
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class Type
{
    /**
     * @var string
     */
    const ARRAY = 'Array';

    /**
     * @var array
     */
    protected static $arTypeList = [];

    /**
     * @return ScalarType
     */
    public static function array(): ScalarType
    {
        if ((static::$arTypeList[self::ARRAY] ?? null) === null) {
            static::$arTypeList[self::ARRAY] = new ArrayType();
        }

        return static::$arTypeList[self::ARRAY];
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return static::$arTypeList;
    }
}
