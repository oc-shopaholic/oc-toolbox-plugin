<?php namespace Lovata\Toolbox\Classes\Item;

/**
 * Class ItemStorage
 * @package Lovata\Toolbox\Classes\Item
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
class ItemStorage
{
    /** @var array */
    protected static $arItemStore = [];

    /**
     * Get item object from storage
     * @param string $sClassName
     * @param int    $iElementID
     * @return ElementItem|null
     */
    public static function get($sClassName, $iElementID)
    {
        $sKey = self::getKey($sClassName, $iElementID);
        if (isset(self::$arItemStore[$sKey]) && self::$arItemStore[$sKey] instanceof ElementItem) {
            return clone self::$arItemStore[$sKey];
        }

        return null;
    }

    /**
     * Set item object in storage
     * @param string      $sClassName
     * @param int         $iElementID
     * @param ElementItem $obItem
     */
    public static function set($sClassName, $iElementID, $obItem)
    {
        if (empty($obItem) || $obItem->isEmpty()) {
            return;
        }

        $sKey = self::getKey($sClassName, $iElementID);
        self::$arItemStore[$sKey] = clone $obItem;
    }

    /**
     * Clear item object in storage
     * @param string $sClassName
     * @param int    $iElementID
     */
    public static function clear($sClassName, $iElementID)
    {
        $sKey = self::getKey($sClassName, $iElementID);
        if (!isset(self::$arItemStore[$sKey])) {
            return;
        }

        unset(self::$arItemStore[$sKey]);
    }

    /**
     * Get store key for item object
     * @param string $sClassName
     * @param int    $iElementID
     * @return string
     */
    protected static function getKey($sClassName, $iElementID)
    {
        return $sClassName.'|'.$iElementID;
    }
}
