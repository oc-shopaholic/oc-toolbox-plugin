<?php namespace Lovata\Toolbox\Classes\Item;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class ItemRegistry
 * Registry "model class => Item class". It is filled by ModelHandler::subscribe().
 * @package Lovata\Toolbox\Classes\Item
 * @author  Pavel Lautsevich, p.lautsevich@lovata.com, LOVATA Group
 */
class ItemRegistry
{
    /** @var array */
    protected static $arItemClassList = [];

    /**
     * Register Item class for model class
     * @param string $sModelClass
     * @param string $sItemClass
     */
    public static function register($sModelClass, $sItemClass)
    {
        if (empty($sModelClass) || empty($sItemClass)) {
            return;
        }

        self::$arItemClassList[ltrim($sModelClass, '\\')] = ltrim($sItemClass, '\\');
    }

    /**
     * Get Item class by model class or morph type
     * @param string $sModelClass
     * @return string|null
     */
    public static function getItemClass($sModelClass)
    {
        if (empty($sModelClass)) {
            return null;
        }

        $sModelClass = Relation::getMorphedModel($sModelClass) ?? $sModelClass;

        return array_get(self::$arItemClassList, ltrim($sModelClass, '\\'));
    }

    /**
     * Clear Item cache of element. Model class without registered Item class is skipped.
     * @param string $sModelClass
     * @param int    $iElementID
     */
    public static function clearCache($sModelClass, $iElementID)
    {
        $sItemClass = self::getItemClass($sModelClass);
        if (empty($sItemClass) || empty($iElementID) || !class_exists($sItemClass)) {
            return;
        }

        $sItemClass::clearCache($iElementID);
    }
}
