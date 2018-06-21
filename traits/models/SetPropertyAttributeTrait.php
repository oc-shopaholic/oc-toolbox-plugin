<?php namespace Lovata\Toolbox\Traits\Models;

/**
 * Trait SetPropertyAttributeTrait
 * @package Lovata\Toolbox\Traits\Models
 * @author  Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 */
trait SetPropertyAttributeTrait
{
    /**
     * Set property attribute, nerge new values with old values
     * @param array $arValue
     */
    protected function setPropertyAttribute($arValue)
    {
        if (is_string($arValue)) {
            $arValue = $this->fromJson($arValue);
        }

        if (empty($arValue) || !is_array($arValue)) {
            return;
        }

        $arPropertyList = $this->property;
        if (empty($arPropertyList)) {
            $arPropertyList = [];
        }

        foreach ($arValue as $sKey => $sValue) {
            $arPropertyList[$sKey] = $sValue;
        }

        $this->attributes['property'] = $this->asJson($arPropertyList);
    }
}
