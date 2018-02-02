<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelHasImages
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
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
        $sErrorMessage = $this->sModelClass.' model has not correct images config';

        /** @var \Model $obModel */
        $obModel = new $this->sModelClass();
        self::assertNotEmpty($obModel->attachMany, $sErrorMessage);
        self::assertArrayHasKey('images', $obModel->attachMany, $sErrorMessage);
        self::assertEquals('System\Models\File', $obModel->attachMany['images'], $sErrorMessage);
    }
}
