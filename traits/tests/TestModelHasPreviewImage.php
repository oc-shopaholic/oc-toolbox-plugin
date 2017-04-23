<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelHasPreviewImage
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kahranenka, a.khoronenko@lovata.com, LOVATA Group
 *
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestModelHasPreviewImage
{
    /**
     * Check model "preview_image" config
     */
    public function testHasPreviewImage()
    {
        $sModelClass = self::MODEL_NAME;
        $sErrorMessage = $sModelClass.' model has not correct preview image config';

        /** @var \Model $obModel */
        $obModel = new $sModelClass();
        self::assertNotEmpty($obModel->attachOne, $sErrorMessage);
        self::assertArrayHasKey('preview_image', $obModel->attachOne, $sErrorMessage);
        self::assertEquals($obModel->attachOne['preview_image'], 'System\Models\File', $sErrorMessage);
    }
}