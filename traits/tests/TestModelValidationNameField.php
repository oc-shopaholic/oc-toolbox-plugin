<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelValidationNameField
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
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
        //Create model object
        /** @var \Model $obModel */
        $obModel = new $this->sModelClass();

        //Get validation rules array and check it
        $arValidationRules = $obModel->rules;
        self::assertNotEmpty($arValidationRules, $this->sModelClass.' model has empty validation rules array');

        //Check rules for "name" field
        self::assertArrayHasKey('name', $arValidationRules, $this->sModelClass.' model not has validation rules for field "name"');
        self::assertNotEmpty($arValidationRules['name'], $this->sModelClass.' model not has validation rules for field "name"');

        $arValidationCondition = explode('|', $arValidationRules['name']);
        self::assertContains('required', $arValidationCondition,$this->sModelClass.' model not has validation rule "required" for field "name"');
    }
}