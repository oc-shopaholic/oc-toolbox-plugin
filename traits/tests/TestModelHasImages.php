<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelHasImages
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestModelHasImages
{
    /**
     * Check model "images" config
     */
    public function testHasImages()
    {
        $sModelClass = self::MODEL_NAME;
        $sErrorMessage = $sModelClass.' model has not correct images config';

        /** @var \Model $obModel */
        $obModel = new $sModelClass();
        self::assertNotEmpty($obModel->attachMany, $sErrorMessage);
        self::assertArrayHasKey('images', $obModel->attachMany, $sErrorMessage);
        self::assertEquals($obModel->attachMany['images'], 'System\Models\File', $sErrorMessage);
    }
}