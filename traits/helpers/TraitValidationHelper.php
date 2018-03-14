<?php namespace Lovata\Toolbox\Traits\Helpers;

use Kharanenka\Helper\Result;

/**
 * Trait TraitValidationHelper
 * @package Lovata\Toolbox\Traits\Helpers
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
trait TraitValidationHelper
{
    /**
     * Process validation error data
     * @param \October\Rain\Database\ModelException $obException
     */
    protected function processValidationError(&$obException)
    {
        $arFiledList = array_keys($obException->getFields());

        Result::setFalse(['field' => array_shift($arFiledList)])
            ->setMessage($obException->getMessage())
            ->setCode($obException->getCode());
    }
}
