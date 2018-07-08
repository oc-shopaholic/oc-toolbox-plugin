<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelValidationSlugField
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestModelValidationSlugField
{
    /**
     * Check model "slug" config
     */
    public function testHasValidationSlugField()
    {
        //Create model object
        /** @var \Model $obModel */
        $obModel = new $this->sModelClass();

        //Get validation rules array and check it
        $arValidationRules = $obModel->rules;
        self::assertNotEmpty($arValidationRules, $this->sModelClass.' model has empty validation rules array');

        //Check rules for "slug" field
        self::assertArrayHasKey('slug', $arValidationRules, $this->sModelClass.' model not has validation rules for field "slug"');
        self::assertNotEmpty($arValidationRules['slug'], $this->sModelClass.' model not has validation rules for field "slug"');

        $arValidationCondition = explode('|', $arValidationRules['slug']);
        self::assertContains('required', $arValidationCondition,$this->sModelClass.' model not has validation rule "required" for field "slug"');
        self::assertContains('unique:'.$obModel->table, $arValidationCondition,$this->sModelClass.' model not has validation rule "unique" for field "slug"');
    }
}