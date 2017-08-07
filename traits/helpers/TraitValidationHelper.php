<?php namespace Lovata\Toolbox\Traits\Helpers;

/**
 * Trait TraitValidationHelper
 * @package Lovata\Toolbox\Traits\Helpers
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 */
trait TraitValidationHelper
{
    /**
     * Get validation error data
     * @param \October\Rain\Exception\ValidationException $obException
     * @return array
     */
    protected function getValidationError($obException)
    {
        $arResult = [
            'message' => null,
            'field'   => null,
        ];

        if(empty($obException)) {
            return $arResult;
        }

        //Get first field name
        $arFieldList = array_keys($obException->getFields());

        $arResult = [
            'message' => $obException->getMessage(),
            'field'   => array_shift($arFieldList),
        ];

        return $arResult;
    }
}