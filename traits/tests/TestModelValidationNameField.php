<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelValidationNameField
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestModelValidationNameField
{
    /**
     * Check model "images" config
     */
    public function testHasValidationNameField()
    {
        $sModelClass = self::MODEL_NAME;

        //Create model object
        /** @var \Model $obModel */
        $obModel = new $sModelClass();

        //Get validation rules array and check it
        $arValidationRules = $obModel->rules;
        self::assertNotEmpty($arValidationRules, self::MODEL_NAME.' model has empty validation rules array');

        //Check rules for "name" field
        self::assertArrayHasKey('name', $arValidationRules, self::MODEL_NAME.' model not has validation rules for field "name"');
        self::assertNotEmpty($arValidationRules['name'], self::MODEL_NAME.' model not has validation rules for field "name"');

        $arValidationCondition = explode('|', $arValidationRules['name']);
        self::assertContains('required', $arValidationCondition,self::MODEL_NAME.' model not has validation rule "required" for field "name"');

    }
}