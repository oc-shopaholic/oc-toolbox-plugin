<?php namespace Lovata\Toolbox\Traits\Tests;

/**
 * Class TestModelHasPreviewImage
 * @package Lovata\Toolbox\Traits\Tests
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
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
        $sErrorMessage = $this->sModelClass.' model has not correct preview image config';

        /** @var \Model $obModel */
        $obModel = new $this->sModelClass();
        self::assertNotEmpty($obModel->attachOne, $sErrorMessage);
        self::assertArrayHasKey('preview_image', $obModel->attachOne, $sErrorMessage);
        self::assertEquals('System\Models\File', $obModel->attachOne['preview_image'], $sErrorMessage);
    }
}