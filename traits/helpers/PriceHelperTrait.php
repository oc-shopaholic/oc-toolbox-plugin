<?php namespace Lovata\Toolbox\Traits\Helpers;

use Model;
use Illuminate\Support\Str;
use Lovata\Toolbox\Classes\Helper\PriceHelper;

/**
 * Trait PriceHelperTrait
 * @package Lovata\Toolbox\Traits\Helpers
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @property array $arPriceField
 */
trait PriceHelperTrait
{
    protected static function bootPriceHelperTrait()
    {
        $sClassName = __CLASS__;
        $sClassName::extend(function ($obElement) {
            /** @var \Model|\Eloquent|\Lovata\Toolbox\Classes\Item\ElementItem $obElement */
            self::addPriceFiledMethods($obElement);
        });
    }

    /**
     * Check arPriceField array and add price methods to model object
     * @param \Model|\Eloquent|\Lovata\Toolbox\Classes\Item\ElementItem $obElement $obElement
     */
    protected static function addPriceFiledMethods($obElement)
    {
        if (empty($obElement->arPriceField) || !is_array($obElement->arPriceField)) {
            return;
        }

        foreach ($obElement->arPriceField as $sFieldName) {
            if (empty($sFieldName) || !is_string($sFieldName)) {
                continue;
            }

            $sFieldConvert = Str::studly($sFieldName);

            self::addGetPriceFieldMethod($obElement, $sFieldName, $sFieldConvert);

            if ($obElement instanceof Model) {
                self::addSetPriceFieldMethod($obElement, $sFieldName, $sFieldConvert);
                self::addGetPriceValueFieldMethod($obElement, $sFieldName, $sFieldConvert);

                self::addScopePriceFieldMethod($obElement, $sFieldName, $sFieldConvert);
            }
        }
    }

    /**
     * Add set{field_name}Attribute methods
     * @param \Model|\Eloquent|\Lovata\Toolbox\Classes\Item\ElementItem $obElement $obElement
     * @param string                                                    $sFieldName
     * @param string                                                    $sFieldConvert
     */
    protected static function addSetPriceFieldMethod($obElement, $sFieldName, $sFieldConvert)
    {
        $sMethodName = 'set'.$sFieldConvert.'Attribute';
        if (method_exists($obElement, $sMethodName)) {
            return;
        }

        $obElement->addDynamicMethod($sMethodName, function ($sValue) use ($sFieldName, $obElement) {

            $fPrice = PriceHelper::toFloat($sValue);
            $obElement->attributes[$sFieldName] = $fPrice;
        });
    }

    /**
     * Add get{field_name}ValueAttribute methods
     * @param \Model|\Eloquent|\Lovata\Toolbox\Classes\Item\ElementItem $obElement $obElement
     * @param string                                                    $sFieldName
     * @param string                                                    $sFieldConvert
     */
    protected static function addGetPriceValueFieldMethod($obElement, $sFieldName, $sFieldConvert)
    {
        $sMethodName = 'get'.$sFieldConvert.'ValueAttribute';
        if (method_exists($obElement, $sMethodName)) {
            return;
        }

        $obElement->addDynamicMethod($sMethodName, function () use ($sFieldName, $obElement) {

            $fPrice = 0;
            if (isset($obElement->attributes[$sFieldName])) {
                $fPrice = $obElement->attributes[$sFieldName];
            }

            return $fPrice;
        });
    }

    /**
     * Add get{field_name}Attribute methods
     * @param \Model|\Eloquent|\Lovata\Toolbox\Classes\Item\ElementItem $obElement $obElement
     * @param string                                                    $sFieldName
     * @param string                                                    $sFieldConvert
     */
    protected static function addGetPriceFieldMethod($obElement, $sFieldName, $sFieldConvert)
    {
        $sMethodName = 'get'.$sFieldConvert.'Attribute';
        if (method_exists($obElement, $sMethodName)) {
            return;
        }

        $obElement->addDynamicMethod($sMethodName, function () use ($sFieldName, $obElement) {

            $sFieldName .= '_value';
            $fPrice = $obElement->$sFieldName;

            $sPrice = PriceHelper::format($fPrice);

            return $sPrice;
        });
    }

    /**
     * Add scopeGetBy{field_name} methods
     * @param \Model|\Eloquent|\Lovata\Toolbox\Classes\Item\ElementItem $obElement $obElement
     * @param string                                                    $sFieldName
     * @param string                                                    $sFieldConvert
     */
    protected static function addScopePriceFieldMethod($obElement, $sFieldName, $sFieldConvert)
    {
        $sMethodName = 'scopeGetBy'.$sFieldConvert;
        if (method_exists($obElement, $sMethodName)) {
            return;
        }

        $obElement->addDynamicMethod($sMethodName, function ($obQuery, $sValue, $sCondition = '=') use ($sFieldName, $obElement) {
            /** @var \October\Rain\Database\Builder $obQuery */
            $sValue = (float) $sValue;

            if (!empty($sCondition)) {
                $obQuery->where($sFieldName, $sCondition, $sValue);
            }

            return $obQuery;
        });
    }
}
