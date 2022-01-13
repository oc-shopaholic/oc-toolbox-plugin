<?php namespace Lovata\Toolbox\Classes\Api\Type;

/**
 * Class TypeFactory
 * @package Lovata\Toolbox\Classes\Api\Type
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class TypeFactory
{
    protected static $obFactory;

    /**
     * Init factory class
     * @param string $sFactoryClass
     */
    public static function init($sFactoryClass)
    {
        static::$obFactory = $sFactoryClass::instance();
    }

    /**
     * @return AbstractTypeFactory
     */
    public static function instance()
    {
        return static::$obFactory;
    }
}
