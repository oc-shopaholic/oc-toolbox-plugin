<?php namespace Lovata\Toolbox\Classes\Api\Type;

/**
 * Class AvailableTypeList
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author  Igor Tverdokhleb, i.tverdokhleb@lovata.com, LOVATA Group
 */
class TypeList
{
    const ENUM_TYPE      = 'EnumType';
    const INPUT_TYPE     = 'InputObjectType';
    const INTERFACE_TYPE = 'InterfaceType';
    const OBJECT_TYPE    = 'ObjectType';
    const UNION_TYPE     = 'UnionType';

    /** @var array */
    public static $arAvailableTypeList = [
        self::ENUM_TYPE,
        self::INPUT_TYPE,
        self::INTERFACE_TYPE,
        self::OBJECT_TYPE,
        self::UNION_TYPE,
    ];

    public static function isValidValue($sType): bool
    {
        return in_array($sType, self::$arAvailableTypeList);
    }
}
