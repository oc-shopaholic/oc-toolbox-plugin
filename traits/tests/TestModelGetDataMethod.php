<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelGetDataMethod
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestModelGetDataMethod
{
    public function testGetDataMethod()
    {
        $sModelClass = self::MODEL_NAME;

        //Create new object
        $obModel = $sModelClass::create($this->arModelData);

        self::assertNotEmpty($obModel, $sModelClass.' creating error');

        //Get model data
        $arModelData = $obModel->getData();
        self::assertNotEmpty($arModelData, $sModelClass.'::getData get empty result');

        //Check field in model data
        foreach($this->arModelData as $sField => $sValue) {

            self::assertArrayHasKey($sField, $arModelData, $sModelClass.'::getData missing "'.$sField.'" field');
            self::assertEquals($arModelData[$sField], $sValue, $sModelClass.'::getData missing "'.$sField.'" field');
        }

        //Get cached model data
        $sModelClass::getCacheData($obModel->id);
        $arModelData = $sModelClass::getCacheData($obModel->id);
        self::assertNotEmpty($arModelData, $sModelClass.'::getCacheData get empty result');

        //Check field in model data
        foreach($this->arModelData as $sField => $sValue) {

            self::assertArrayHasKey($sField, $arModelData, $sModelClass.'::getCacheData missing "'.$sField.'" field');
            self::assertEquals($arModelData[$sField], $sValue, $sModelClass.'::getCacheData missing "'.$sField.'" field');
        }
    }
}