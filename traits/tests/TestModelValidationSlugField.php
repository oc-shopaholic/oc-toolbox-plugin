<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelValidationSlugField
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestModelValidationSlugField
{
    /**
     * Check model "images" config
     */
    public function testHasValidationSlugField()
    {
        $sModelClass = self::MODEL_NAME;

        //Create model object
        /** @var \Model $obModel */
        $obModel = new $sModelClass();

        //Get validation rules array and check it
        $arValidationRules = $obModel->rules;
        self::assertNotEmpty($arValidationRules, self::MODEL_NAME.' model has empty validation rules array');

        //Check rules for "slug" field
        self::assertArrayHasKey('slug', $arValidationRules, self::MODEL_NAME.' model not has validation rules for field "slug"');
        self::assertNotEmpty($arValidationRules['slug'], self::MODEL_NAME.' model not has validation rules for field "slug"');

        $arValidationCondition = explode('|', $arValidationRules['slug']);
        self::assertContains('required', $arValidationCondition,self::MODEL_NAME.' model not has validation rule "required" for field "slug"');
        self::assertContains('unique:'.$obModel->table, $arValidationCondition,self::MODEL_NAME.' model not has validation rule "unique" for field "slug"');
    }
}